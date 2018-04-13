<?php

class Venues_API extends Program_Routes {

    /**
     * Enregistre les routes pour l'API d'export de la programmation (lieux).
     */
    public function register_routes(){
        $args = array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_venues' ),
                'permission_callback' => array( $this, 'get_venues_permissions_check' ),
                'format' => 'xml',
                'args' => array(),
            ),
        );

        register_rest_route( $this->namespace, '/' . $this->base.'venues', $args);
        register_rest_route( $this->namespace, '/' . $this->base.'/venues', $args);
    }

    /**
     * Retourne tous les lieux.
     *
     * @param WP_REST_Request $request Information sur la requête.
     * @return WP_Error|WP_REST_Response
     */
    public function get_venues( $request ){
        $data = array('venues' => array());

        $venues = get_posts(array(
            'post_type' => 'venue',
            'numberposts' => -1
        ));

        foreach($venues as $venue){
            $data['venues'][] = $this->prepare_venues_for_response( $venue, $request );
        }

        return new WP_REST_Response( $data, 200 );
    }


    /**
     * Prépare l'élément pour la réponse de l'API.
     *
     * @param mixed $venue Lieu en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_venues_for_response( $venue, $request ) {
        $en_id = apply_filters( 'wpml_object_id', $venue->ID, $venue->post_type, true, 'en' );
        $fr_id = apply_filters( 'wpml_object_id', $venue->ID, $venue->post_type, true, 'fr' );

        $content_en = $this->get_dynamic_content($en_id, array('description'));
        $content_fr = $this->get_dynamic_content($fr_id, array('description'));

        $venue_data = array(
            'key' => 'venue',
            'value' => array(
                'id' => array(
                    'value' => $venue->ID
                ),
                'title' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_the_title($en_id) ? get_the_title($en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_the_title($fr_id) ? get_the_title($fr_id) : ''),
                            'attr' => array(
                                'lang' => 'fra'
                            )
                        )
                    )
                ),
                'description' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => ($content_en ? $content_en : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => ($content_fr ? $content_fr : ''),
                            'attr' => array(
                                'lang' => 'fra'
                            )
                        )
                    )
                ),
                'gpsLatitude' => array(
                    'value' => (get_field('adresse', $fr_id) ? get_field('adresse', $fr_id)['lat'] : '')
                ),
                'gpsLongitude' => array(
                    'value' => (get_field('adresse', $fr_id) ? get_field('adresse', $fr_id)['lng'] : '')
                )
            )
        );
        
        return $venue_data;
    }


    

    /**
     * Vérifie si la requête peut query les événements.
     *
     * @param WP_REST_Request $request Objet de la requête.
     * @return WP_Error|bool
     */
    public function get_venues_permissions_check( $request ){
        return true;
    }
}