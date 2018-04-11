<?php

class Program_Routes extends WP_REST_Controller {

    /**
     * Enregistre les routes pour l'API d'export de la programmation (events).
     */
    public function register_routes(){
        $version = '1';
        $namespace = 'programs/v' . $version;
        $base = 'export';

        register_rest_route( $namespace, '/' . $base, array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_events' ),
                'permission_callback' => array( $this, 'get_events_permissions_check' ),
                'format' => 'xml',
                'args' => array(),
            ),
        ) );
    }

    /**
     * Retourne tous les événements.
     *
     * @param WP_REST_Request $request Information sur la requête.
     * @return WP_Error|WP_REST_Response
     */
    public function get_events( $request ){
        $events = get_posts(array(
            'post_type' => 'program',
            'numberposts' => -1,
            'order' => 'ASC',
            'order_by' => 'meta_value',
            'meta_key' => 'date_et_heure',
        ));
        $data = array();

        foreach($events as $event){
            $data[] = $this->prepare_event_for_response( $event, $request );
        }

        return new WP_REST_Response( $data, 200 );
    }


    /**
     * Prépare l'élément pour la réponse de l'API.
     *
     * @param mixed $item Événement en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_event_for_response( $event, $request ) {
        $event_data = array(
            'key' => 'event',
            'value' => array(
                'title' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => $event->post_title,
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => $event->post_title,
                            'attr' => array(
                                'lang' => 'fra'
                            )
                        )
                    )
                ),
                'subtitle' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => 'English subtitle',
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => 'French subtitle',
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
                            'value' => 'English description',
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => 'French description',
                            'attr' => array(
                                'lang' => 'fra'
                            )
                        )
                    )
                ),
                'photo' => array(
                    'value' => wp_get_attachment_url(get_field('image_de_levenement', $event->ID)),
                    'attr' => array(
                        'updateDate' => get_the_modified_date('Y-m-d H:i', get_field('image_de_levenement', $event->ID))
                    )
                )

            )
        );

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