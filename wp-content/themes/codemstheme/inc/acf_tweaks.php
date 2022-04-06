<?php 
/**
 * Class ACF_Tweaks
 * Description: Classe servant aux modifications de ACF.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ACF_Tweaks' ) ) :
	

/**
 * Classe servant aux modifications de ACF.
 *
 * @category Core
 * @author Codems
 * @version 1.0.0
 */	
final class ACF_Tweaks {
	
	/**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;
	
	
	/**
	 * Initialise l'instance de la Classe.
	 *
	 * Fait en sorte que seulement une instance de la Classe est disponible.
	 *
	 * @return ACF_Tweaks - Main instance
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
		$this->install();
		$this->init_hooks();
	}
	
	
	/**
	 * Installe le plugin qui se trouve dans le theme.
	 */
	private function install(){
		include_once( get_template_directory() . '/acf-pro/acf.php' );
		add_filter('acf/settings/path', array( $this, 'acf_settings_path' ));
		add_filter('acf/settings/dir', array( $this, 'acf_settings_dir' ));
	}
	
	
	/**
	 * Change le path pour les settings.
	 *
	 * @see ACF_Tweaks::install()
	 *
	 * @param string $path
	 * @return string
	 */
	public function acf_settings_path( $path ) {
	    // update path
	    $path = get_template_directory() . '/acf-pro/';
	    return $path;
	}
	
	
	/**
	 * Change le directory pour les settings.
	 *
	 * @see ACF_Tweaks::install()
	 *
	 * @param string $dir
	 * @return string
	 */
	public function acf_settings_dir( $dir ) {
	    // update uri
	    $dir = get_template_directory_uri() . '/acf-pro/';
	    return $dir;
	}
	
	
	/**
	 * Enregistre les hooks requis.
	 *
	 * Appelé une seule fois par __construct.
	 * 
	 * @see ACF_Tweaks::__construct()
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'register_options' ), 0 );
		add_filter('acf/settings/save_json', array($this, 'save_in_child_json_folder') );
		add_filter('acf/settings/load_json', array($this, 'load_parent_json_folder') );
		
		add_filter('acf/load_field/name=article_personnalise', array($this, 'add_custom_post_type_in_options') );
	}
	
	
	/**
	 * Sauvegarde les acf-json dans le theme child.
	 * 
	 * @return String
	 */
	public function save_in_child_json_folder(){
		if(is_child_theme()){
			return get_stylesheet_directory() . '/acf-json';
		}
		
		return get_template_directory() . '/acf-json';
	}
	
	
	/**
	 * Utilise le acf-json du parent et du child.
	 *
	 * Cela nous permet d'avoir les champs du parent et du child en même temps.
	 * 
	 * @param Array $paths all the paths to load
	 * @return Array
	 */
	public function load_parent_json_folder($paths){
		$paths = array();
		
		if(is_child_theme()){
			$paths[] = get_stylesheet_directory() . '/acf-json';
		}
		
		$paths[] = get_template_directory() . '/acf-json';

		return $paths;
	}
	
	
	/**
	 * Ajoute la page d'option principale.
	 */
	public function register_options() {
		if( function_exists('acf_add_options_page') ) {
			acf_add_options_page();
		}
	}
	
	
	/**
	 * Ajoute tous les custom posts type dans le select article_personnalise.
	 * 
	 * @param Array $field Valeurs du champ.
	 * @see CPT
	 * @return Array
	 */
	public function add_custom_post_type_in_options( $field ) {
		// reset choices
		$field['choices'] = array();
		
		$cpts = CPT()->_cpts;
		
		$field['choices']['post'] = __('Post', 'custom_theme');

		foreach ($cpts as $cpt) {
			$cpt_obj = get_post_type_object($cpt);
			$field['choices'][$cpt] =  $cpt_obj->labels->singular_name;
		}
		
		// return the field
		return $field;
	}
	
	
}// ACF_Tweaks Class
	
/**
 * Retourne l'instance de la Classe.
 * 
 * @see ACF_Tweaks
 */
function ACF_Tweaks() {
	return ACF_Tweaks::instance();
}
ACF_Tweaks();

endif;
?>