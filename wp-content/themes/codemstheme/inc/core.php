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
		$this->includes();
		$this->init_hooks();

		show_admin_bar( false );
	}

	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );

		add_action( 'wp_enqueue_scripts', array( $this, 'registerScripts' ), 10 );
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
	 * Inclu les fichiers principaux.
	 */
	public function includes() {
		// include theme update
		require_once 'lib/theme-updates/theme-update-checker.php';

		// Mobile detect class
		include_once( 'Mobile_Detect.php' );

		do_action('cdm_add_modules_start');

		// MenuWalker
		include_once( 'MenuWalker.php' );

		// Helpers
		include_once('helpers.php');

		// Custom Post Types
		include_once( 'cpt.php' );

		// Image profile
		include_once( 'lib/user_avatar/user_avatar.php' );

		// SendMail
		//include_once( 'lib/send_mail/sendMail.php' );

		// ACF Tweaks
		include_once( 'acf_tweaks.php' );

		// WooCommerce Tweaks
		include_once( 'woo_tweaks.php' );

		// Mailchimp add_address
		include_once( 'add_address.php' );

		// Sections Loader
		include_once( 'sections_loader.php' );

		do_action('cdm_add_modules_end');
	}


	/**
	 * Déclanche la Classe lorsque WordPress est initialisé.
	 */
	public function init() {
		$this->checkUpdateTheme();

		$this->detect = new Mobile_Detect();
		$this->themeVersion = wp_get_theme($this->theme)->get('Version');

		$this->register_route_structure();
		$this->addImagesSizes();
		$this->addUserRole();
		$this->updateUserRole();
		$this->register_menus();

		do_action('TCore_init');

		$this->acf_google_map_key();

		do_action('TCore_ready');
	}



	/**
	 * Vérifie les mises à jours du theme.
	 */
	private function checkUpdateTheme(){
		$update_checker = new ThemeUpdateChecker(
		    $this->theme,
		    'http://wp-themeupdater-codemstheme.codems.ca/info.json'
		);
		$update_checker->checkForUpdates();
	}



	/**
	 * Crée des dimensions d'images.
	 */
	private function addImagesSizes(){
		add_image_size( 'max-banner', 2000 );
		add_image_size( 'max-mobile', 640 );
		add_image_size( 'square', 150, 150 );
	}



	/**
	 * Crée des menu pour le theme.
	 *
	 * @TODO Valider l'importance de cette fonction.
	 */
	private function register_menus(){
		// Register additionnal menus here

		// register_nav_menus( array(
		// 	'topbar1' => 'Top brun gauche',
		// 	'topbar2' => 'Top brun droit',
		// ) );
	}



	/**
	 * Crée les règles de rewrite url.
	 *
	 * @TODO Valider l'importance de cette fonction.
	 */
	private function register_route_structure(){
		global $wp_rewrite;
		// Add rewrite rules here
	}



	/**
	 * Fourni la clé api de Google Map à ACF.
	 */
	function acf_google_map_key() {
		acf_update_setting('google_api_key', $this->gmapKey);
	}



	/**
	 * Crée le role pour le client.
	 */
	private function addUserRole(){
		if (run_once('add_user_role_client_admin')){
			//do you stuff and it will only run once
			global $wp_roles;
		    if ( ! isset( $wp_roles ) )
		        $wp_roles = new WP_Roles();

		    $adm = $wp_roles->get_role('administrator');
		    //Adding a 'admin_client' with all admin caps
		    $wp_roles->add_role('admin_client', 'Administrateur client', $adm->capabilities);
		}
	}


	/**
	 * Met à jour le role du client sur l'administrateur à de nouveau role.
	 */
	private function updateUserRole(){
		global $wp_roles;
		if ( ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();

		$adminRole = $wp_roles->get_role('administrator');
		$userRole = $wp_roles->get_role('admin_client');

		$missingCaps = array_diff_key($adminRole->capabilities, $userRole->capabilities);
		if($missingCaps){
			foreach ($missingCaps as $key => $value) {
				$userRole->add_cap( $key, $value );
			}
		}
	}


	/**
	 * Retire les administrateurs du dropdown pour les utilisateurs non admin.
	 */
	public function remove_administrator_management($all_roles){
		if(!current_user_can('administrator')) { // is not a administrator
	        unset($all_roles['administrator']);
	    }

	    return $all_roles;

	}


	/**
	 * Retire les administrateurs de la liste pour les utilisateurs non admin.
	 */
	public function remove_admin_from_list($args){
		if(!current_user_can('administrator'))
			$args['role__not_in'] = array('administrator');

		return $args;
	}


	/**
	 * Retire les administrateurs de la navigation pour les utilisateurs non admin.
	 */
	public function remove_administrator_in_nav($views){
		if(!current_user_can('administrator'))
			unset($views['administrator']);

		return $views;
	}


	/**
	 * Retire les actions pour le theme actuel.
	 */
	public function disable_theme_actions($themes){
		$parentTheme = wp_get_theme()->get('Template');
		if($parentTheme){
			$themes[$parentTheme]['actions']['activate'] = NULL;
			$themes[$parentTheme]['actions']['customize'] = NULL;
		}

		return $themes;
	}


	/**
	 * Cache les action pour le theme actuel.
	 */
	public function hide_theme_actions($themes){
		$parentTheme = wp_get_theme()->get('Template');
		if($parentTheme){
			?>
			<style type="text/css">
				.themes .theme[data-slug="<?= $parentTheme ?>"] .theme-actions{
					display: none;
				}
			</style>
			<?php
		}
	}


	/**
	 * Vérifie que toutes les images ont un attribut "alt".
	 *
	 * Si l'image n'a pas d'attribut "alt", on prend le nom du site.
	 *
	 * @param Array $attr
	 * @param WP_Post $attachment
	 * @param String $size
	 * @return Array
	 */
	public function dynamicImageAlt($attr, $attachment, $size){
		if($attr['alt'] == ''){
			$attr['alt'] = esc_attr( get_bloginfo( 'name', 'display' ) );
		}

		return $attr;
	}


	/**
	 * Ajoute les hooks requis pour la colonne "alt" dans la page média.
	 */
	public function hook_new_media_columns(){
		add_filter( 'manage_media_columns', array($this, 'media_alt_column') );
		add_action( 'manage_media_custom_column', array($this, 'media_alt_value'), 10, 2 );
		add_filter( 'manage_upload_sortable_columns', array($this, 'media_alt_column_sortable') );
	}


	/**
	 * Ajoute la colonne alt dans la page médias.
	 *
	 * @param Array $cols Toutes les colonnes.
	 * @return Array
	 */
	public function media_alt_column( $cols ) {
        $cols["alt"] = "Texte alternatif (alt)";
        return $cols;
	}


	/**
	 * Valeur de la colonne alt.
	 *
	 * @param String $column_name
	 * @param Int $id
	 */
	public function media_alt_value( $column_name, $id ) {
		echo trim( strip_tags( get_post_meta( $id, '_wp_attachment_image_alt', true ) ) );
	}


	/**
	 * Rend la colonne alt triable.
	 *
	 * @param Array $cols
	 * @return Array
	 */
	public function media_alt_column_sortable( $cols ) {
		$cols["alt"] = "alt";

		return $cols;
	}


	/**
	 * Style le login de WordPress.
	 *
	 * @TODO Rendre la fonction personnalisable par le theme enfant.
	 */
	public function my_login_logo() {
		echo '<style type="text/css">
		body.login { position:relative; background-color: #59595b; }

		#login { padding-top:5%; position:relative; z-index:10; }
		.login h1 a {
			background-image: url('. THEMEURI.'/images/logo.png' .')!important;
			width:auto!important;
			background-size:100% auto!important;
			background-position:center center!important;
			height:140px!important;
			width:140px!important;
			box-sizing:border-box!important;
		}
		.login #nav{display:none;}
		#backtoblog { padding:10px !important; }
		#backtoblog a{color:#ffffff !important; -webkit-transition:all 0.3s;transition:all 0.3s;}
		#backtoblog a:hover{color:#f79521 !important;}

		.wp-core-ui .button-primary {
			background-color: transparent !important;
			border:3px solid #8cb7e8 !important;
			line-height: normal!important;
			-webkit-box-shadow:none!important;
			box-shadow:none!important;
			font-family:"Raleway",sans-serif;
			border: medium none;
			border-radius: 20px !important;
			color: #6d6e71 !important;
			font-size: 1.3rem;
			font-weight: 500!important;
			padding:0 4px;
			-webkit-transition:all 0.3s;
			transition:all 0.3s;
			text-shadow:none !important;
			text-transform: uppercase;
			font-family: "Source Sans Pro",Helvetica,sans-serif !important;
		}

		.login form #user_login{
			border:none; font-size: 1.2rem; background-color:rgba(99, 101, 103, 0.12); padding:8px 10px; color:#231f20; font-family: "Oswald", sans-serif;
		}

		.login #login_error, .login .message{border-left:#8cb7e8 4px solid !important;}
		.wp-core-ui .button-primary:hover { background:transparent!important; color:#f79521 !important;}
		</style>';
	}



	/**
	 * Change le lien pour le logo de la page login.
	 */
	public function my_login_logo_url() {
		return esc_url(home_url('/'));
	}



	/**
	 * Retire les widgets du dashboard.
	 */
	public function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        //remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
       //remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
	}



	/**
	 * Enregistre les styles et les scripts pour le frontend.
	 */
	public function registerScripts(){
		// ALL GOOGLE FONTS ARE GENERATED IN FUNCTION.PHP
		wp_enqueue_style("photoSwipeStyle", THEMEURI."/css/photoswipe.css" , false, $this->themeVersion);
		wp_enqueue_style("photoSwipeStyleDefault", THEMEURI."/css/default-skin/default-skin.css" , false, $this->themeVersion);

		// load all the fa-5 css
		wp_enqueue_style("fontawesome-core", THEMEURI."/css/fontawesome/fontawesome-pro-core.css" , false, $this->themeVersion);
		wp_enqueue_style("fontawesome-brands", THEMEURI."/css/fontawesome/fontawesome-pro-brands.css" , array('fontawesome-core'), $this->themeVersion);
		wp_enqueue_style("fontawesome-light", THEMEURI."/css/fontawesome/fontawesome-pro-light.css" , array('fontawesome-core'), $this->themeVersion);
		wp_enqueue_style("fontawesome-solid", THEMEURI."/css/fontawesome/fontawesome-pro-solid.css" , array('fontawesome-core'), $this->themeVersion);
		wp_enqueue_style("fontawesome-regular", THEMEURI."/css/fontawesome/fontawesome-pro-regular.css" , array('fontawesome-core'), $this->themeVersion);
		wp_enqueue_style("fontawesome-svg", THEMEURI."/css/fontawesome/fontawesome-pro-svg-framework.css" , array('fontawesome-core'), $this->themeVersion);

		// load fa-4 before fa-5 in case of missing rule in the early access
		// will be removed when fa-5 is officially release
		wp_enqueue_style("font-awesome-4", THEMEURI."/css/font-awesome.min.css" , array('fontawesome-core'), $this->themeVersion);


		wp_enqueue_style("mmenustyle", THEMEURI."/css/jquery.mmenu.all.css" , false, $this->themeVersion);
		wp_enqueue_style("css-owl-carousel", THEMEURI."/css/owl.carousel.min.css" , false, $this->themeVersion);
		wp_enqueue_style("css-owl-theme-carousel", THEMEURI."/css/owl.theme.default.min.css" , false, $this->themeVersion);
		wp_enqueue_style("cssparallax", THEMEURI."/css/parallax.css" , false, $this->themeVersion);

		// SCRIPTS
		wp_deregister_script( 'jquery' );
		wp_enqueue_script("jquery", "//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" , false, "2.1.1");

		if($this->gmapKey != ''){
			wp_enqueue_script("google-map", "//maps.googleapis.com/maps/api/js?key=". $this->gmapKey ."&v=3.exp&libraries=places", array('jquery'), '3.0.0');
		}

		wp_enqueue_script("photoSwipe", THEMEURI."/js/photoswipe.min.js" , array('jquery'), $this->themeVersion, true);
		//wp_enqueue_script("cycle2", THEMEURI."/js/cycle-2.js" , false, $this->themeVersion);
		wp_enqueue_script("js-owl-carousel", THEMEURI."/js/owl.carousel.min.js" , array('jquery'), $this->themeVersion, true);
		wp_enqueue_script("customSelect", THEMEURI."/js/jquery.customSelect.min.js" , array('jquery'), $this->themeVersion, true);
		wp_enqueue_script("mmenu", THEMEURI."/js/jquery.mmenu.all.min.js" , array('jquery'), $this->themeVersion, true);
		wp_enqueue_script("theme-utils", THEMEURI."/js/theme.utils.js" , array('jquery'), $this->themeVersion, true);
		wp_enqueue_script("jsparallax", THEMEURI."/js/parallax.js", array('theme-utils'), $this->themeVersion, true);

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