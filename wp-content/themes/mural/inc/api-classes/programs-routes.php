<?php

class Program_Routes extends WP_REST_Controller {

    public $version = '1';
    public $namespace = 'programs/v';
    public $base = 'export';

    function __construct(){
        $this->namespace .= $this->version;
    }


    /**
     * Tri le contenu dynamique d'une page pour en resortir le texte en format compatible avec L'API.
     * 
     * @param Int $post_id
     * @param Array $custom_fields Contient les noms des champs à utiliser au lieu du champs contenu par défaut.
     * @return String
     */
    public function get_dynamic_content($post_id, $custom_fields = array()){
        $allowable_tags = '<br><i><b><em><strong>';
        $valid_row = array(
            'titre_section',
            'texte_pleine_largeur'
        );

        $stripped_dynamic_content = '';

        if(!empty($custom_fields)){
            foreach ($custom_fields as $field) {
                $stripped_dynamic_content .= strip_tags(get_field($field, $post_id), $allowable_tags).'<br>';
            }

        }else{
            if ( have_rows( 'contenu', $post_id ) ) {
                while ( have_rows('contenu', $post_id ) ) { the_row();
                    switch (get_row_layout()) {
                        case 'titre_section':
                            $stripped_dynamic_content .= '<strong>'.get_sub_field('titre').'</strong><br>';
                            break;
                        
                        case 'texte_pleine_largeur':
                            $stripped_dynamic_content .= strip_tags(get_sub_field('texte_pleine_largeur'), $allowable_tags).'<br>';
                            break;
                    }
                }
            }
        }

        return trim($stripped_dynamic_content, '<br>');
    }
}