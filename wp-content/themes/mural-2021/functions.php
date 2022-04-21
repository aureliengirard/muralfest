<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'gallery', 'audio', ));
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts' ) );
		parent::__construct();
	}

	function register_post_types() {
		require('lib/custom_types.php');
	}

	function register_taxonomies() {
		require('lib/custom_taxonomies.php');
	}

	function add_to_context( $context ) {
		$context['options'] = get_fields('options');

		$context['nav_menu'] = new TimberMenu('Menu Principal EN');
		$context['secondary_nav_menu'] = new TimberMenu('Secondary Nav');
        $context['footer_menu'] = new TimberMenu('footer_menu');

		// post
		$args = array(
		    'post_type'  => 'post',
		    'posts_per_page' => 3,
		    'post_status' => 'publish'
		);
		$context['blog_posts'] = new Timber\PostQuery($args);

		// program
		$args = array(
			'post_type'=> 'program',
			'post__in' => get_sub_field('evenements'),
			'posts_per_page' => -1,
			'orderby' => 'post__in'
		);

		$context['event_posts'] = new Timber\PostQuery($args);

		$context['blog_posts_link'] = get_post_type_archive_link('post');

		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		require_once('lib/custom_functions.php');
		/* this is where you can add your own functions to twig */

		//$twig->addExtension(new Twig_Extensions_Extension_Intl());
		$twig->addExtension(new Twig_Extension_StringLoader());

		$twig->addFunction( new Timber\Twig_Function( 'get_breadcrumb', 'get_breadcrumb' ) );
		$twig->addFunction( new Timber\Twig_Function( 'get_back_button', 'get_back_button' ) );
		$twig->addFunction( new Timber\Twig_Function( 'get_current_language', 'get_current_language' ) );

		return $twig;
	}

	public function addImagesSizes(){
		add_image_size( 'artwork-thumb', 150, 200 );
	}

	function loadScripts() {
		wp_enqueue_script("jquery-js",
			"//code.jquery.com/jquery-3.6.0.min.js",
			array('jquery'),
			wp_get_theme()->get('Version'),
			true
		);

		$gmapKey = 'AIzaSyDdu7rV-v4gA4aeUX6bEbKkoVD9Jy8B-E4';
		if($gmapKey != ''){
			wp_enqueue_script("google-map", "//maps.googleapis.com/maps/api/js?key=". $gmapKey ."&v=3.exp&libraries=places", array('jquery'), '3.0.0');
		}

		wp_enqueue_script("artworks-map", get_template_directory_uri() ."/src/js/vendor/map-arts.js", array('jquery'), '1.0.0', true);

		wp_enqueue_script("map-script",
			get_template_directory_uri() ."/src/js/vendor/map.js",
			array('jquery'),
			wp_get_theme()->get('Version'),
			true
		);

		wp_enqueue_script("script",
			get_template_directory_uri() ."/js/script.js",
			array('theme-utils'),
			wp_get_theme()->get('Version'),
			true
		);

		/* enqueue style */
		wp_enqueue_style( 'select2-style',
			'//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css',
			null,
			wp_get_theme()->get('Version')
		);
		
		wp_enqueue_style( 'jqueryui-style',
			'//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
			null,
			wp_get_theme()->get('Version')
		);
	}

}

new StarterSite();

//include_once('inc/core.php');
include_once('inc/helpers.php');
include_once('inc/mural-api.php');
include_once('inc/class-Festival.php');

add_filter( 'timber/twig', function( $twig ) {
    $twig->addFilter( new Twig_SimpleFilter( 'date_from_format', function( $date, $input_format, $output_format ) {
        return date_i18n(
            $output_format,
            DateTime::createFromFormat( $input_format, $date )->getTimeStamp()
        );
    } ) );

    return $twig;
});

//To create ACF Options page, just uncomment below:
if(function_exists('acf_add_options_page')) {
    acf_add_options_page();
}

/**
 * If the redirection URL is a WordPress page or post, specify here its WordPress ID
 */
/*
add_action( 'template_redirect', function() {
	$id = get_field('splash_page', 'options');
	
	if ( is_page( $id ) ) {
		return;
	}
	
	wp_redirect( esc_url_raw( home_url( '?page_id='.$id ) ), 307 );
	exit;
} );
*/

/**
 * Envoi plus de variable PHP au script map.js
 *
 */
function add_script_data($phpData){
    $phpData['siteURL'] = esc_url( home_url( '/' ) );
	$phpData['childURI'] = get_stylesheet_directory_uri();

    return $phpData;
}
add_filter('php_data_to_scriptjs', 'add_script_data', 10, 1 );

/**
 * Envoie les informations requise pour le calendrier
 *
 */
function send_date_to_calendar(){
	$minmax = array(
		'min' => '',
		'max' => ''
	);
	$args = array(
		'post_type' => array( 'program' ),
		'posts_per_page' => -1,
		'orderby' => array(
			'order_event' => 'ASC',
		),
		'meta_query' => array(
			'order_event' => array(
				'key' => 'event_date'
			)
		)
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ){
		while ( $query->have_posts() ){
			$query->the_post();

			$event_start = strtotime(get_field('event_date'));
			$event_end = strtotime(get_field('date_de_fin'));

			if($event_start < $minmax['min'] || !$minmax['min']){
				$minmax['min'] = $event_start;
			}

			if($event_end > $minmax['max'] || !$minmax['max']){
				$minmax['max'] = $event_end;
			}
		}

		wp_reset_postdata();

		$minmax['min'] = array(
			'year' => date('Y', $minmax['min']),
			'month' => date('m', $minmax['min']),
			'day' => date('d', $minmax['min'])
		);

		$minmax['max'] = array(
			'year' => date('Y', $minmax['max']),
			'month' => date('m', $minmax['max']),
			'day' => date('d', $minmax['max'])
		);
	}

	wp_localize_script( 'daterange', 'datelimit', $minmax);

	wp_localize_script( 'daterange', 'translation', array(
		'reset' => __('Reset', 'site-theme'),
		'done' => __('Done', 'site-theme')
	));
}
add_action( 'wp_enqueue_scripts', 'send_date_to_calendar', 30 );


/**
 * Retourne le ID de la page associé au CPT.
 */
function get_posttype_parent_id($post_type = NULL){
    $page_id = false;

    if(!$post_type)
        $post_type = get_post_type();


    if ( have_rows( 'liaison_cpt_pages', 'options' ) ){
        while ( have_rows( 'liaison_cpt_pages', 'options' ) ){ the_row();
            if(get_sub_field( 'article_personnalise' ) == $post_type)
                $page_id = get_sub_field( 'page' );
        }
    }

    return $page_id;
}

/**
 * Retourne les images de tous les oeuvres d'un artiste.
 *
 * @param Int $artist_id
 * @return Array
 */
function get_artist_artworks_images($artist_id){
	$artwork_args = array(
        'post_type' => array('artwork'),
        'posts_per_page' => -1,
        'meta_key' => 'artiste',
        'meta_value' => $artist_id,
        'orderby' => 'annee',
        'order' => 'ASC'
    );

	$artwork_query =  new WP_Query($artwork_args);

	$artworks_images = array();

	if($artwork_query->have_posts()){
		while ($artwork_query->have_posts()){
			$artwork_query->the_post();

			if(get_field('image_de_loeuvre')){
				$artworks_images[] = array(
					'id' => get_the_ID(),
					'year' => get_field('annee'),
					'image_id' => get_field('image_de_loeuvre')
				);
			}

		}
	}

	wp_reset_postdata();

	return $artworks_images;
}

function my_acf_google_map_api( $gmapKey ){
    $api['key'] = 'AIzaSyDdu7rV-v4gA4aeUX6bEbKkoVD9Jy8B-E4';
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');