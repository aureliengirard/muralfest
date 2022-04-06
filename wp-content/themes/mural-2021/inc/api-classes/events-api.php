<?php

class Events_API extends Program_Routes {

    /**
     * Enregistre les routes pour l'API d'export de la programmation (events).
     */
    public function register_routes(){
        $args = array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_events' ),
                'permission_callback' => array( $this, 'get_events_permissions_check' ),
                'format' => 'xml',
                'args' => array(),
            ),
        );

        register_rest_route( $this->namespace, '/' . $this->base.'events', $args);
        register_rest_route( $this->namespace, '/' . $this->base.'/events', $args);
    }

    /**
     * Retourne tous les événements et les catégories d'événements.
     *
     * @param WP_REST_Request $request Information sur la requête.
     * @return WP_Error|WP_REST_Response
     */
    public function get_events( $request ){
        $data = array('events' => array());

        $event_terms = get_terms( array(
            'taxonomy' => 'event-category',
            'hide_empty' => false,
            'suppress_filters' => false
        ) );

        foreach($event_terms as $term){
            $data['events'][] = $this->prepare_terms_for_response( $term, $request );
        }

        $events = get_posts(array(
            'post_type' => 'program',
            'numberposts' => -1,
            'suppress_filters' => false,
            'order' => 'ASC',
            'orderby' => array(
                'order_event' => 'ASC',
                'order_start_time' => 'ASC'
            ),
            'meta_query' => array(
                'order_event' => array(
                    'key' => 'event_date'
                ),
                'order_start_time' => array(
                    'key' => 'heure_de_debut'
                )
            )
        ));

        foreach($events as $event){
            $data['events'][] = $this->prepare_events_for_response( $event, $request );
        }

        return new WP_REST_Response( $data, 200 );
    }


    /**
     * Prépare la taxonomie pour la réponse de l'API.
     *
     * @param mixed $term taxonomie en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_terms_for_response( $term, $request ) {
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
                'id' => $term->term_id,
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
     * @param mixed $event Événement en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_events_for_response( $event, $request ) {
        $en_id = apply_filters( 'wpml_object_id', $event->ID, $event->post_type, true, 'en' );
        $fr_id = apply_filters( 'wpml_object_id', $event->ID, $event->post_type, true, 'fr' );

        $content_en = $this->get_dynamic_content($en_id);
        $content_fr = $this->get_dynamic_content($fr_id);

        $event_data = array(
            'key' => 'event',
            'value' => array(
                'id' => array(
                    'value' => $event->ID
                ),
                'discoverable' => (get_field('discover', $fr_id) ? get_field('discover', $fr_id) : 0),
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
                'subtitle' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_field('sous_titre', $en_id) ? get_field('sous_titre', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('sous_titre', $fr_id) ? get_field('sous_titre', $fr_id) : ''),
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
                            'value' => $content_en,
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => $content_fr,
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                ),
                'photo' => array(
                    'value' => wp_get_attachment_image_url(get_field('image_de_levenement', $fr_id), 'api-event'),
                    'attr' => array(
                        'updateDate' => get_the_modified_date('Y-m-d H:i', get_field('image_de_levenement', $fr_id))
                    )
                ),
                'link1' => array(
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
                ),
                'link2' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_field('lien_evenement_facebook', $en_id) ? get_field('lien_evenement_facebook', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('lien_evenement_facebook', $fr_id) ? get_field('lien_evenement_facebook', $fr_id) : ''),
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                ),
                'link3' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_field('lien_playlist', $en_id) ? get_field('lien_playlist', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('lien_playlist', $fr_id) ? get_field('lien_playlist', $fr_id) : ''),
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                )
            )
        );
        

        $terms = wp_get_post_terms( $fr_id, 'event-category' );
        if(!empty($terms)){
            foreach ($terms as $term) {
                $event_data['value']['tags'][] = array(
                    'key' => 'tagId',
                    'value' => $term->term_id
                );
            }
        }

        return $event_data;
    }


    /**
     * Vérifie si la requête peut query les événements.
     *
     * @param WP_REST_Request $request Objet de la requête.
     * @return WP_Error|bool
     */
    public function get_events_permissions_check( $request ){
        return true;
    }
}