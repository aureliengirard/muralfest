<?php 
/**
 * Class CPT
 * Description: Classe aillant pour but de faciliter l'ajout des custom posts type et des taxonomies.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 *
 * @category Core
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'CPT' ) ) :
	
/**
 * Classe aillant pour but de faciliter l'ajout des custom posts type et des taxonomies.
 *
 * @category Core
 * @author Codems
 * @version 1.0.0
 */	

final class CPT {
	
	/**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;
	
	/**
	 * Tous les custom posts type ajouté.
	 * @var Cpts
	 */
	public $_cpts = array();
	
	/**
	 * Détermine si le cpt doit être accordé au masculin ou au féminin
	 * @var CptOrth
	 */
	public $cptOrth = 'm';
	
	/**
	 * Mini dictionnaires pour les masculin/féminin
	 * @var BasicDicts
	 */
	private $basicDicts = array();
	
	
	
	/**
	 * Initialise l'instance de la Classe.
	 *
	 * Fait en sorte que seulement une instance de la Classe est disponible.
	 *
	 * @static
	 * @return CPT - Main instance
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
		$this->init_hooks();
	}
	
	
	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}
	
	
	
	/**
	 * Déclanche la Classe lorsque WordPress est initialisé.
	 * 
	 * @hook CPT-ready Utiliser ce hook pour ajouter des cpt et taxonomie
	 */
	public function init() {
		$this->basicDicts = array(
			'new' => array(
				'm' => _x('New', 'masculin', 'custom_theme'),
				'f' => _x('New', 'feminin', 'custom_theme'),
			),
			'all' => array(
				'm' => _x('All', 'masculin', 'custom_theme'),
				'f' => _x('All', 'feminin', 'custom_theme'),
			),
			'one' => array(
				'm' => _x('One', 'masculin', 'custom_theme'),
				'f' => _x('One', 'feminin', 'custom_theme'),
			),
			'no' => array(
				'm' => _x('No', 'masculin', 'custom_theme'),
				'f' => _x('No', 'feminin', 'custom_theme'),
			)
		);

		do_action('CPT-ready');
	}
	
	
	
	/**
	 * Enregistre un custom post type.
	 * 
	 * @param String $cpt_name Nom du custom post type.
	 * @param Array $cpt_args Permet de modifier les valeurs par défaut.
	 * 
	 * @return Bool
	 */
	public function register_custom_post_type($cpt_name, $cpt_args = array()){
		if(empty($cpt_args))
			return false;
		
		$defaultCPT = array(
			'cpt_variations' => array(),
			'labels' => array(),
			'args' => array()
		);
		
		$cpt_args = wp_parse_args( $cpt_args, $defaultCPT );
		
		$cpt_variations = $cpt_args['cpt_variations'];
		$labels = $cpt_args['labels'];
		$args = $cpt_args['args'];
		
		if(empty($cpt_variations) || (!isset($cpt_variations['singular']) || !isset($cpt_variations['plural']) ) ){
			return false;
		}
		
		$defaultLabels = array(
			'name' => $cpt_variations['plural'],
			'singular_name' => $cpt_variations['singular'],
			'add_new' => $this->get_word('new') .' '. $cpt_variations['singular'],
			'add_new_item' => $this->get_word('new') .' '. $cpt_variations['singular'],
			'edit_item' => __('Edit', 'custom_theme') .' '. $cpt_variations['singular'],
			'new_item' => $this->get_word('new') .' '. $cpt_variations['singular'],
			'all_items' => $this->get_word('all') .' '. __('the', 'custom_theme') .' '. $cpt_variations['plural'],
			'view_item' => __('View', 'custom_theme') .' '. $cpt_variations['plural'],
			'search_items' => __('Search', 'custom_theme') .' '. strtolower($this->get_word('one')) .' '. $cpt_variations['plural'],
			'not_found' =>  $this->get_word('no') .' '. $cpt_variations['singular'],
			'not_found_in_trash' => $this->get_word('no') .' '. $cpt_variations['singular'] .' '. __('in trash', 'custom_theme'),
		);
		
		$labels = wp_parse_args( $labels, $defaultLabels );
	   
		$defaultArgs = array(
			'labels' => $labels,
			'taxonomies' => array(),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => array('slug' => $cpt_name, 'with_front' => true),
			'query_var' => true,
			'show_in_nav_menus'=> false,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-tickets-alt',
			'supports' => array('title', 'editor', 'thumbnail')
		);
		
		$args = wp_parse_args( $args, $defaultArgs );
	   	
	   	$this->_cpts[] = $cpt_name;  // Array contenant tous les CPT custom
		register_post_type( $cpt_name , $args );
		
		return true;
	}// Activités
	
	
	
	
	
	/**
	 * Ajoute une taxonomie.
	 * 
	 * @param String $tax_name Nom de la taxonomie.
	 * @param Array $tax_args Permet de modifier les valeurs par défaut.
	 * 
	 * @return Bool
	 */
	public function add_taxonomy($tax_name, $tax_args){
		if(empty($tax_args))
			return false;
		
		$defaultTax = array(
			'custom_posts' => array('post'),
			'tax_variations' => array(),
			'labels' => array(),
			'args' => array()
		);
		
		$tax_args = wp_parse_args( $tax_args, $defaultTax );
		
		$custom_posts = $tax_args['custom_posts'];
		$tax_variations = $tax_args['tax_variations'];
		$labels = $tax_args['labels'];
		$args = $tax_args['args'];
		
		if(empty($tax_variations) || (!isset($tax_variations['singular']) || !isset($tax_variations['plural']) ) ){
			return false;
		}
		
		$defaultLabels = array(
			'name'              => $tax_variations['plural'],
			'singular_name'     => $tax_variations['singular'],
			'search_items'      => __('Search', 'custom_theme') .' '. $tax_variations['singular'],
			'all_items'         => $this->get_word('all') .' '. __( 'the', 'custom_theme') .' '. $tax_variations['plural'],
			'parent_item'       => $tax_variations['singular'] .' '. __( 'parent', 'custom_theme' ),
			'parent_item_colon' => $tax_variations['singular'] .' '. __( 'parent:', 'custom_theme' ),
			'edit_item'         => __( 'Edit', 'custom_theme' ) .' '. $tax_variations['singular'],
			'update_item'       => __( 'Udate', 'custom_theme' ),
			'add_new_item'      => __( 'Add', 'custom_theme' ) .' '. strtolower($this->get_word('new')) .' '. $tax_variations['singular'],
			'new_item_name'     => __( 'New name for', 'custom_theme' ) .' '. $tax_variations['singular'],
			'not_found'			=> $this->get_word('no') .' '. $tax_variations['singular'],
			'menu_name'         => $tax_variations['singular'],
		);
		
		$labels = wp_parse_args( $labels, $defaultLabels );
	   
		$defaultArgs = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $tax_name ),
		);
		
		$args = wp_parse_args( $args, $defaultArgs );
		
		
		register_taxonomy($tax_name, $custom_posts, $args );
		
	}// 
	
	
	/**
	 * Retourne un mot du dictionnaire de la Classe et retourne le bon genre.
	 * 
	 * @param String $word Le mot à trouver.
	 * 
	 * @return Bool|String
	 */
	private function get_word($word){
		if(!isset($this->basicDicts[$word]))
			return false;
			
		return $this->basicDicts[$word][$this->cptOrth];
	}
	
	
}// CPT Class
	
/**
 * Retourne l'instance de la Classe.
 * 
 * @see CPT
 */
function CPT() {
	return CPT::instance();
}
CPT();

endif;
?>