<?php
/**
 * Class Festival
 * Description: Manipule tous se qui touche aux festivals, de l'ajout à l'affichage.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 *
 * @category Addons
 * @author Codems
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Festival' ) ) :

/**
 * Manipule tous se qui touche aux festivals, de l'ajout à l'affichage.
 *
 * @category Addons
 * @author Codems
 * @version 1.0.0
 */

class Festival {

    /**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;


    /**
     * Nom du template festival
     * @var TemplateName
     */
    private $template_name = 'template-festival.php';


    /**
     * Post type / taxonomie relatif au festival
     */
    private $restricted_post_type = array();


    /**
     * Festival actuel
     * @var Festival
     */
    private $current_festival = NULL;


    /**
     * Classe des artworks si existante.
     * @var Artworks
     */
    public $artworks = NULL;


    /**
	 * Initialise l'instance de la Classe.
	 *
	 * Fait en sorte que seulement une instance de la Classe est disponible.
	 *
	 * @static
	 * @return TCore - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructeur de la Classe.
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
        add_action( 'wp', array( $this, 'init' ), 0 );

        add_action( 'after_setup_theme', array( $this, 'addImagesSizes' ), 0 );
        add_action( 'init', array( $this, 'tier_image_sizes' ), 10 );

        add_filter('acf/load_field/name=festival', array( $this, 'add_all_festival_to_select' ) );
        //add_filter('acf/load_field/name=annee', array( $this, 'dynamic_add_year' ) );
        add_filter('display_post_states', array( $this, 'add_post_state_festival' ), 10, 2);

        add_action( 'admin_init', array($this, 'add_festival_columns') );
        add_action( 'pre_get_posts', array( $this, 'alter_query_for_festival' ) );
    }




	/**
	 * Inclu les fichiers principaux.
	 */
	public function includes() {
        include_once('class-Artworks.php');
    }


    /**
	 * Crée les dimensions des images requis.
     *
	 */
	public function addImagesSizes(){
        add_image_size( 'artwork-thumb', 150, 200 );
    }


    /**
     * Crée les dimensions custom pour les différents niveaux des partenaires.
     */
    public function tier_image_sizes(){
        $tiers = get_terms( array(
            'taxonomy' => 'tier',
            'orderby' => 'term_order',
            'order' => 'ASC'
        ) );

        foreach($tiers as $tier){
            $width = get_field('largeur', 'tier_'.$tier->term_id);

            if($width)
                add_image_size( 'tier-'.$tier->slug, $width );
        }
    }


    /**
	 * Déclanche la Classe lorsque WordPress est initialisé.
	 */
	public function init() {
        $page_id = get_the_ID();

        if(is_page($page_id)){
            if(get_page_template_slug($page_id) == $this->template_name){
                $this->current_festival = $page_id;

            }else{
                $page_ancestors = get_post_ancestors( $page_id );

                foreach($page_ancestors as $ancestor_id){
                    if(get_page_template_slug($ancestor_id) == $this->template_name){
                        $this->current_festival = $ancestor_id;
                        break;
                    }
                }
            }

            $this->artworks = new Artworks();

        }
    }


    /**
	 * Ajoute les hooks requis pour la colonne festival dans les cpts.
	 */
	public function add_festival_columns(){
        foreach($this->restricted_post_type as $post_type){
            add_filter( 'manage_'.$post_type.'_posts_columns', array($this, 'festival_column') );
            add_action( 'manage_'.$post_type.'_posts_custom_column', array($this, 'festival_value'), 10, 2 );
            add_filter( 'manage_edit-'.$post_type.'_sortable_columns', array($this, 'festival_column_sortable') );
        }
	}


	/**
	 * Ajoute la colonne festival dans les cpts.
	 *
	 * @param Array $cols Toutes les colonnes.
	 * @return Array
	 */
	public function festival_column( $cols ) {
        $columns = array_slice($cols, 0, 2, true);
        $columns["festival"] = "Festival";
        $columns = array_merge($columns, array_slice($cols, 2, count($cols)-2, true));

        return $columns;
	}


	/**
	 * Nom du festival.
	 *
	 * @param String $column_name
	 * @param Int $id
	 */
	public function festival_value( $column_name, $id ) {
        if($column_name == 'festival'){
            echo trim( strip_tags( get_the_title(get_field('festival', $id)) ) );
        }
	}


	/**
	 * Rend la colonne festival triable.
	 *
	 * @param Array $cols
	 * @return Array
	 */
	public function festival_column_sortable( $cols ) {
		$cols["festival"] = "festival";

		return $cols;
	}


    /**
     * Modifie toutes les query si on est dans un festival
     * pour retourner seulement les posts associé au festival.
     *
     * @param Object $query
     */
    public function alter_query_for_festival($query){
        if(!is_admin()){
            if($this->current_festival){
                $post_types = (Array) $query->get('post_type');

                if(count(array_intersect($post_types, $this->restricted_post_type))){
                    $meta_query = array(
                        'relation' => 'OR',
                        array(
                            'key' => 'festival',
                            'compare' => 'NOT EXISTS'
                        ),
                        array(
                            'key' => 'festival',
                            'value' => $this->current_festival
                        )
                    );

                    $current_meta_query = (Array) $query->get('meta_query');
                    $current_meta_query[] = $meta_query;

                    $query->set('meta_query', $current_meta_query);
                }
            }
        }
    }


    /**
     * Ajoute tous les festivals dans le select des Œuvres.
     *
     * @param Array $field
     * @return Array
     */
	public function add_all_festival_to_select( $field ){
        // reset choices
        $field['choices'] = array();

        $festivals = get_pages(array(
            'child_of' => $this->current_festival,
            'meta_key' => '_wp_page_template',
            'meta_value' => $this->template_name,
        ));

        foreach ($festivals as $festival) {
            $field['choices'][$festival->ID] =  $festival->post_title;
        }

        return $field;
    }



    /**
     * Identifie les pages festival dans le backend.
     *
     * @param Array $states
     * @param Object $post
     * @return Array
     */
    public function add_post_state_festival( $states, $post ) {

        if( 'page' == get_post_type( $post->ID ) && $this->template_name == get_post_meta( $post->ID, '_wp_page_template', true ) ){

                $states[] = __('Festival', 'site-theme');

        }

        return $states;
    }


    /**
     * Retourne le festival actuel
     *
     * @return Int|Bool
     */
    public function get_festival(){
        return $this->current_festival;
    }


    /**
     * Retour le ID de la sous page désiré
     *
     * @param String $template_name
     * @return Int
     */
    public function get_festival_sub_page($template_name){
        if($this->current_festival){

            $sub_page = get_pages(array(
                'child_of' => $this->current_festival,
                'meta_key' => '_wp_page_template',
                'meta_value' => 'template-'.$template_name.'.php',
            ));

            if($sub_page){
                return $sub_page[0]->ID;
            }
        }

        return false;
    }


}// Festival Class

/**
 * Retourne l'instance de la Classe.
 *
 * @see Festival
 */
function Festival() {
	return Festival::instance();
}
Festival();

endif;