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

        $tag_terms = get_terms( array(
            'taxonomy' => 'tag-venue',
            'hide_empty' => false,
            'suppress_filters' => false
        ) );

        foreach($tag_terms as $term){
            $res = $this->prepare_terms_for_response( $term, $request );
            
            if($res)
                $data['venues'][] = $this->prepare_terms_for_response( $term, $request );
        }

        $venues = get_posts(array(
            'post_type' => 'venue',
            'numberposts' => -1,
            'suppress_filters' => false
        ));

        foreach($venues as $venue){
            $data['venues'][] = $this->prepare_venues_for_response( $venue, $request );
        }
        
        // Ajoute les lieux des oeuvres
        $artworks = get_posts(array(
            'post_type' => 'artwork',
            'numberposts' => -1,
            'suppress_filters' => false
        ));

        foreach($artworks as $artwork){
            $data['venues'][] = $this->prepare_artworks_for_response( $artwork, $request );
        }

        //var_dump($data);exit;

        return new WP_REST_Response( $data, 200 );
    }


    /**
     * Prépare la taxonomie pour la réponse de l'API.
     * On ajoute par défaut "Murale" pour les lieux des oeuvres.
     * 
     * @param mixed $term taxonomie en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_terms_for_response( $term, $request ) {
        if(strpos($term->slug, 'gc-venuetag') !== false)
            return NULL;

        $en_id = apply_filters( 'wpml_object_id', $term->term_id, $term->taxonomy, true, 'en' );
        $fr_id = apply_filters( 'wpml_object_id', $term->term_id, $term->taxonomy, true, 'fr' );

        global $sitepress;
        remove_filter('get_term', array($sitepress,'get_term_adjust_id'), 1, 1);

        $en_term = get_term($en_id, $term->taxonomy);
        $fr_term = get_term($fr_id, $term->taxonomy);

        add_filter('get_term', array($sitepress,'get_term_adjust_id'), 1, 1);

        $term_data = array(
            'key' => 'tag',
            'value' => array(
                'id' => $term->slug,
                'title' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => ($en_term->name ? $en_term->name : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => ($fr_term->name ? $fr_term->name : ''),
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                )
            )
        );

        return $term_data;
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
                                'lang' => 'fre'
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
                                'lang' => 'fre'
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

        $terms = wp_get_post_terms( $fr_id, 'tag-venue' );
        if(!empty($terms)){
            foreach ($terms as $term) {
                $venue_data['value']['tags'][] = array(
                    'key' => 'tagId',
                    'value' => $term->slug
                );
            }
        }
        
        return $venue_data;
    }



    /**
     * Prépare l'élément pour la réponse de l'API.
     *
     * @param mixed $artwork Lieu en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_artworks_for_response( $artwork, $request ) {
        $en_id = apply_filters( 'wpml_object_id', $artwork->ID, $artwork->post_type, true, 'en' );
        $fr_id = apply_filters( 'wpml_object_id', $artwork->ID, $artwork->post_type, true, 'fr' );

        $content_en = $this->get_dynamic_content($en_id, array('description_de_loeuvre'));
        $content_fr = $this->get_dynamic_content($fr_id, array('description_de_loeuvre'));

        $venue_data = array(
            'key' => 'venue',
            'value' => array(
                'id' => array(
                    'value' => $artwork->ID
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
                                'lang' => 'fre'
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
                                'lang' => 'fre'
                            )
                        )
                    )
                ),
                'photo' => array(
                    'value' => wp_get_attachment_url(get_field('image_de_loeuvre', $fr_id)),
                    'attr' => array(
                        'updateDate' => get_the_modified_date('Y-m-d H:i', get_field('image_de_loeuvre', $fr_id))
                    )
                ),
                'tags' => array(
                    'tagId' => 'venue-tag-mural-id'
                ),
                'gpsLatitude' => array(
                    'value' => (get_field('lieu_de_loeuvre', $fr_id) ? get_field('lieu_de_loeuvre', $fr_id)['lat'] : '')
                ),
                'gpsLongitude' => array(
                    'value' => (get_field('lieu_de_loeuvre', $fr_id) ? get_field('lieu_de_loeuvre', $fr_id)['lng'] : '')
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