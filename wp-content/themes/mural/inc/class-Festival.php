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

        add_action( 'CPT-ready', array( $this, 'register_festival_CPT') );
        add_filter('acf/load_field/name=festival', array( $this, 'add_all_festival_to_select' ) );
        add_filter('acf/load_field/name=annee', array( $this, 'dynamic_add_year' ) );
        add_filter('display_post_states', array( $this, 'add_post_state_festival' ), 10, 2);

        add_action( 'admin_init', array($this, 'add_festival_columns') );
        add_action( 'pre_get_posts', array( $this, 'alter_query_for_festival' ) );
    }
    

    /**
     * 
     */
    function register_festival_CPT(){
        $festival_CPT = array();

        CPT()->cptOrth = 'f'; // accorde les mots au cpt
        CPT()->register_custom_post_type('artwork', array(
            'cpt_variations' => array(
                'singular' => _x('Artwork', 'post type singular name', 'site-theme'),
                'plural' => _x('Artworks', 'post type general name', 'site-theme')
            ),
            'args' => array(
                'menu_icon' => 'dashicons-art',
                'supports' => array('title', 'editor')
            )
        ));
        $festival_CPT[] = 'artwork';
        
        CPT()->cptOrth = 'm'; // accorde les mots au cpt
        CPT()->register_custom_post_type('artist', array(
            'cpt_variations' => array(
                'singular' => _x('Artist', 'post type singular name', 'site-theme'),
                'plural' => _x('Artists', 'post type general name', 'site-theme')
            ),
            'args' => array(
                'menu_icon' => 'dashicons-id',
                'supports' => array('title')
            )
        ));

        CPT()->cptOrth = 'm'; // accorde les mots au cpt
        CPT()->register_custom_post_type('partner', array(
            'cpt_variations' => array(
                'singular' => _x('Partner', 'post type singular name', 'site-theme'),
                'plural' => _x('Partners', 'post type general name', 'site-theme')
            ),
            'args' => array(
                'menu_icon' => 'dashicons-groups',
                'supports' => array('title'),
                'publicly_queryable'  => false,
                'exclude_from_search' => true
            )
        ));
        $festival_CPT[] = 'partner';

        CPT()->cptOrth = 'm'; // accorde les mots au cpt
        CPT()->register_custom_post_type('program', array(
            'cpt_variations' => array(
                'singular' => _x('Event', 'post type singular name', 'site-theme'),
                'plural' => _x('Events', 'post type general name', 'site-theme')
            ),
            'labels' => array(
                'add_new' => _x('New', 'masculin nouvel', 'site-theme') .' '. _x('event', 'post type singular name', 'site-theme'),
                'add_new_item' => _x('New', 'masculin nouvel', 'site-theme') .' '. _x('event', 'post type singular name', 'site-theme'),
                'new_item' => _x('New', 'masculin nouvel', 'site-theme') .' '. _x('event', 'post type singular name', 'site-theme'),
            ),
            'args' => array(
                'menu_icon' => 'dashicons-location-alt',
                'supports' => array('title', 'editor')
            )
        ));
        $festival_CPT[] = 'program';
        
        CPT()->cptOrth = 'm';
        CPT()->add_taxonomy('tier', array(
            'custom_posts' => array('partner'),
            'tax_variations' => array(
                'singular' => _x('Tier', 'taxonomy singular name', 'site-theme'),
                'plural' => _x('Tiers', 'taxonomy general name', 'site-theme')
            )
        ));

        $this->restricted_post_type = $festival_CPT;
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

            if($this->current_festival && get_page_template_slug($page_id) == 'template-maps.php'){
                $artworks = new Artworks();
            }
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

                $query->set('meta_query', $meta_query);
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
    
        $args = array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_wp_page_template',
                    'value' => $this->template_name
                )
            )
        );
        $query = new WP_Query( $args );
        
        if( $query->have_posts() ){
            while( $query->have_posts() ){
                $query->the_post();
                
                $field['choices'][get_the_ID()] =  get_the_title();
    
            }
        }
        wp_reset_postdata();
    
        return $field;
    }


    /**
     * Ajoute dans le select de l'année toutes les années depuis la création du festival.
     * 
     * @param Array $field
     * @return Array
     */
	public function dynamic_add_year( $field ){
        // reset choices
        $field['choices'] = array();
    
		$current_year = (Int) date('Y');
		$starting_year = 2012;
		
		for ($i=$current_year; $i >= $starting_year; $i--) { 
			$field['choices'][$i] = $i;
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