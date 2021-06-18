<?php

/**
 * Retourne les événements, mais formatté selon le format horaire (show).
 */
class Shows_API extends Program_Routes {

    /**
     * Enregistre les routes pour l'API d'export de la programmation (shows).
     */
    public function register_routes(){
        $args = array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_shows' ),
                'permission_callback' => array( $this, 'get_shows_permissions_check' ),
                'format' => 'xml',
                'args' => array(),
            ),
        );

        register_rest_route( $this->namespace, '/' . $this->base.'shows', $args);
        register_rest_route( $this->namespace, '/' . $this->base.'/shows', $args);
    }

    /**
     * Retourne tous les événements.
     *
     * @param WP_REST_Request $request Information sur la requête.
     * @return WP_Error|WP_REST_Response
     */
    public function get_shows( $request ){
        $data = array('shows' => array());

        $shows = get_posts(array(
            'post_type' => 'program',
            'numberposts' => -1,
            'suppress_filters' => false,
            'order' => 'ASC',
            'order_by' => 'meta_value',
            'meta_key' => 'event_date',
        ));

        foreach($shows as $show){
            $data['shows'][] = $this->prepare_shows_for_response( $show, $request );
        }

        return new WP_REST_Response( $data, 200 );
    }


    /**
     * Prépare l'élément pour la réponse de l'API.
     *
     * @param mixed $show Événement en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_shows_for_response( $show, $request ) {
        $en_id = apply_filters( 'wpml_object_id', $show->ID, $show->post_type, true, 'en' );
        $fr_id = apply_filters( 'wpml_object_id', $show->ID, $show->post_type, true, 'fr' );

        $date_start = date('Y-m-d', strtotime(get_field('event_date', $fr_id)));
        $date_end = date('Y-m-d', strtotime(get_field('date_de_fin', $fr_id)));
        $time_start = (get_field('heure_de_debut', $fr_id) ? date('H:i', strtotime(get_field('heure_de_debut', $fr_id))) : '');
        $time_end = (get_field('heure_de_fin', $fr_id) ? date('H:i', strtotime(get_field('heure_de_fin', $fr_id))) : '');

        $show_data = array(
            'key' => 'show',
            'value' => array(
                'id' => array(
                    'value' => $show->ID
                ),
                'dateStart' => array(
                    'value' => ($date_start ? $date_start : '')
                ),
                'timeStart' => array(
                    'value' => ($time_start ? $time_start : '')
                ),
                'dateEnd' => array(
                    'value' => ($date_end ? $date_end : $date_start)
                ),
                'timeEnd' => array(
                    'value' => ($time_end ? $time_end : '')
                ),
                'eventId' => array(
                    'value' => $show->ID
                ),
                'venueId' => array(
                    'value' => get_field('lieu', $fr_id)
                ),
                'linkTicket' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_field('lien_billets', $en_id) ? get_field('lien_billets', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('lien_billets', $fr_id) ? get_field('lien_billets', $fr_id) : ''),
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                )
            )
        );

        return $show_data;
    }


    /**
     * Vérifie si la requête peut query les événements.
     *
     * @param WP_REST_Request $request Objet de la requête.
     * @return WP_Error|bool
     */
    public function get_shows_permissions_check( $request ){
        return true;
    }
}