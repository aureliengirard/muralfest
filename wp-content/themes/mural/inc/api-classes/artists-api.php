<?php

class Artists_API extends Program_Routes {

    /**
     * Enregistre les routes pour l'API d'export de la programmation (artists).
     */
    public function register_routes(){
        $args = array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_artists' ),
                'permission_callback' => array( $this, 'get_artists_permissions_check' ),
                'format' => 'xml',
                'args' => array(),
            ),
        );

        register_rest_route( $this->namespace, '/' . $this->base.'artists', $args);
        register_rest_route( $this->namespace, '/' . $this->base.'/artists', $args);
    }

    /**
     * Retourne tous les artistes.
     *
     * @param WP_REST_Request $request Information sur la requête.
     * @return WP_Error|WP_REST_Response
     */
    public function get_artists( $request ){
        $data = array('artists' => array());

        $artists = get_posts(array(
            'post_type' => 'artist',
            'numberposts' => -1,
            'suppress_filters' => false
        ));

        foreach($artists as $artist){
            $data['artists'][] = $this->prepare_artists_for_response( $artist, $request );
        }
        
        return new WP_REST_Response( $data, 200 );
    }


    /**
     * Prépare l'élément pour la réponse de l'API.
     *
     * @param mixed $artist Artiste en object wordpress.
     * @param WP_REST_Request $request Objet de la requête.
     * @return mixed
     */
    public function prepare_artists_for_response( $artist, $request ) {
        $en_id = apply_filters( 'wpml_object_id', $artist->ID, $artist->post_type, true, 'en' );
        $fr_id = apply_filters( 'wpml_object_id', $artist->ID, $artist->post_type, true, 'fr' );

        $content_en = $this->get_dynamic_content($en_id, array('biographie'));
        $content_fr = $this->get_dynamic_content($fr_id, array('biographie'));

        $style_en = (get_field('style', $en_id) ? get_field('style', $en_id) : "");
        $style_fr = (get_field('style', $fr_id) ? get_field('style', $fr_id) : "");

        if(is_array($style_en)){
            $style_en = implode(", ", $style_en);
        }

        if(is_array($style_fr)){
            $style_fr = implode(", ", $style_fr);
        }
        

        $artist_data = array(
            'key' => 'artist',
            'value' => array(
                'id' => array(
                    'value' => $artist->ID
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
                'country' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_field('pays_dorigine', $en_id) ? get_field('pays_dorigine', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('pays_dorigine', $fr_id) ? get_field('pays_dorigine', $fr_id) : ''),
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                ),
                'style' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => $style_en,
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => $style_fr,
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
                'link1' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_field('siteweb', $en_id) ? get_field('siteweb', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('siteweb', $fr_id) ? get_field('siteweb', $fr_id) : ''),
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
                            'value' => (get_field('instagram', $en_id) ? get_field('instagram', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('instagram', $fr_id) ? get_field('instagram', $fr_id) : ''),
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
                            'value' => (get_field('facebook', $en_id) ? get_field('facebook', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('facebook', $fr_id) ? get_field('facebook', $fr_id) : ''),
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                ),
                'link4' => array(
                    'value' => array(
                        array(
                            'key' => 'value',
                            'value' => (get_field('twitter', $en_id) ? get_field('twitter', $en_id) : ''),
                            'attr' => array(
                                'lang' => 'eng'
                            )
                        ),
                        array(
                            'key' => 'value',
                            'value' => (get_field('twitter', $fr_id) ? get_field('twitter', $fr_id) : ''),
                            'attr' => array(
                                'lang' => 'fre'
                            )
                        )
                    )
                )
            )
        );

        return $artist_data;
    }


    

    /**
     * Vérifie si la requête peut query les événements.
     *
     * @param WP_REST_Request $request Objet de la requête.
     * @return WP_Error|bool
     */
    public function get_artists_permissions_check( $request ){
        return true;
    }
}