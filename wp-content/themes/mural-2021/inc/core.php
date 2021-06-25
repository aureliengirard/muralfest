<?php
/**
 * Class TCore
 * Description: La Classe principale du theme qui installe tous les fonctionnalitées.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 *
 * @category Core
 * @author Codems
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCore' ) ) :

/**
 * La Classe principale du theme qui installe tous les fonctionnalitées.
 *
 * @category Core
 * @author Codems
 * @version 1.0.0
 */

final class TCore {

	/**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;

	/** Le text domain pour les traductions.
	 * Retirer pour mauvaise pratique : http://ottopress.com/2012/internationalization-youre-probably-doing-it-wrong/
	 */
	// public $textDom = 'custom_theme';

	/**
	 * Clé de l'api pour Google Map.
	 *
	 * @var GmapKey
	 */
	public $gmapKey = '';


	/**
	 * Instance de la Classe de détection pour mobile.
	 *
	 * @var Detect
	 */
	public $detect = null;

	/**
	 * Nom du theme.
	 *
	 * @var Theme
	 */
	public $theme = 'codemstheme';

	/**
	 * Version du theme.
	 *
	 * @var ThemeVersion
	 */
	public $themeVersion = '1.0'; // version is set on init


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
		if (session_status() == PHP_SESSION_NONE) { // Pour éviter une erreur si une session existe déjà
		    session_start();
		}
		$this->define_constants();
		$this->init_hooks();

		show_admin_bar( false );
	}

	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );

		add_action( 'wp_enqueue_scripts', array( $this, 'sendDataJS' ), 30 );

		add_filter('wp_prepare_themes_for_js', array( $this, 'disable_theme_actions') );
		add_action( 'admin_enqueue_scripts', array($this, 'hide_theme_actions') );

		add_filter( 'wp_get_attachment_image_attributes', array($this, 'dynamicImageAlt'), 10, 3 );

		//add_action( 'login_enqueue_scripts', array($this, 'my_login_logo') );
		add_filter( 'login_headerurl', array($this, 'my_login_logo_url') );

		add_action( 'admin_init', array($this, 'hook_new_media_columns') );

		add_action( 'admin_init', array($this, 'remove_dashboard_meta') );

		// remove administrator for non admin users
		add_action('editable_roles', array($this, 'remove_administrator_management') );
		add_filter('users_list_table_query_args', array( $this, 'remove_admin_from_list'), 10, 1 );
		add_filter('views_users', array( $this, 'remove_administrator_in_nav'), 10, 1 );
	}

	/**
	 * Ajoute les constantes requis.
	 */
	private function define_constants() {
		$this->define( 'THEMEURI', get_template_directory_uri() );
		$this->define( 'RELATIVEROOT', __DIR__.'/../' );
	}

	/**
	 * Permet l'ajout de constantes seulement si elle n'existe pas déjà.
	 * @param string $name
	 * @param string|bool $value
	 */
	public function define( $name, $value ) {
		if ( !defined($name) ) {
			define( $name, $value );
		}
	}






	/**
	 * Envoie des variables vers certains scripts.
	 *
	 * @hook php_traduction_to_js
	 * @hook php_data_to_scriptjs
	 * @hook php_data_to_mapjs
	 */
	public function sendDataJS(){
		// Localize the script with new data
		$traduction = array(
			'itineraire' => __('Directions to this place', 'custom_theme'),
			'adresse' => __('Address', 'custom_theme'),
			'emailError' => __('Please enter a valid email address.', 'custom_theme'),
            'mail_mess_1' => __( 'Processing your request. Please wait.', 'custom_theme'),
        	'mail_mess_success' => __( 'Your email address has been added. Thank you!', 'custom_theme'),
		);

		$traduction = apply_filters( 'php_traduction_to_js', $traduction );

		wp_localize_script( 'script', 'traduction', $traduction );
		wp_localize_script( 'map', 'traduction', $traduction );

		// general data
		$cdmConf = array(
			'mobile_width' => 768
		);

		$cdmConf = apply_filters( 'php_cdmConf_js', $cdmConf );

		wp_localize_script( 'theme-utils', 'cdmConf', $cdmConf );

		// script.js
		$phpData = array(
			'current_url_without_params' =>	get_permalink(),
			'THEMEURI' => THEMEURI,
			'homeURL' => esc_url( home_url( '/' ) ),
			'siteName' => esc_attr( get_bloginfo( 'name', 'display' ) ),
			'lang' => 'fr'
		);

		if( defined('ICL_LANGUAGE_CODE') ){
			$phpData['lang'] = ICL_LANGUAGE_CODE;
		}

		$phpData = apply_filters( 'php_data_to_scriptjs', $phpData );

		wp_localize_script( 'script', 'phpData', $phpData );

		// map.js
		$mapData = array(
			'current_url_without_params' =>	get_permalink(),
			'themeURI' => THEMEURI,
			'homeURL' => esc_url( home_url( '/' ) ),
			'siteName' => esc_attr( get_bloginfo( 'name', 'display' ) ),
			'gmap' => get_field('adresse', 'options')
		);

		$mapData = apply_filters( 'php_data_to_mapjs', $mapData );

		wp_localize_script( 'map', 'mapData', $mapData );
	}


}// TCore Class

/**
 * Retourne l'instance de la Classe.
 *
 * @see TCore
 */
function TCore() {
	return TCore::instance();
}
TCore();

endif;