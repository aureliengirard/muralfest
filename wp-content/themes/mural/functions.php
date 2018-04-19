<?php
include_once('inc/mural-api.php');
include_once('inc/class-Festival.php');
include_once('inc/tinymce_formats.php');

function add_google_map_key(){
    TCore()->gmapKey = 'AIzaSyCF3WxcpgQ24K5pFkWR8en9WMyEpkY4JwQ';
}
add_action( 'TCore_init', 'add_google_map_key'); // on TCore init


function child_theme_setup() {
	/*
	 * Makes it available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'site-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'child_theme_setup' );


/* Is called when TCore is ready */
function on_TCore_ready(){
    TCore()->define('CHILDURI', get_stylesheet_directory_uri());
}
add_action( 'TCore_ready', 'on_TCore_ready');



function register_CPT(){
    
}
//add_action( 'CPT-ready', 'register_CPT');


/**
 * Register image sizes
 * 
 * @hooked init
 */
function cdm_add_images_sizes(){
    add_image_size( 'cta-preview', 400, 285, array('center', 'center') );
}
add_action( 'init', 'cdm_add_images_sizes' );


/*********
/* Register script and style in relation with the parent theme
*********/
function theme_enqueue_styles() {
	/* enqueue script */
	wp_enqueue_script("jqueryui-js",
		"//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js",
		array('jquery'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("daterange",
		CHILDURI."/js/daterange-calendar.js",
		array('jqueryui-js'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("ink",
		CHILDURI."/js/ink.js",
		array('theme-utils'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("map",
		CHILDURI."/js/map.js",
		array('theme-utils'),
		wp_get_theme()->get('Version'),
		true
	);
	
	wp_enqueue_script("script",
		CHILDURI."/js/script.js",
		array('theme-utils'),
		wp_get_theme()->get('Version'),
		true
	);
	
	/* enqueue style */
	wp_enqueue_style( 'jqueryui-style',
		'//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
		null,
		wp_get_theme()->get('Version')
	);

	wp_enqueue_style( 'child-style',
		CHILDURI . '/style.css',
		null,
		wp_get_theme()->get('Version')
	);

	wp_dequeue_style('font-awesome-4');
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 11 );



/**
 * Return the Google font stylesheet URL, if available.
 *
*/
function theme_fonts_url() {
	$fonts_url = '';
	
	$font_families = array();
	
	$font_families[] = 'Open Sans:300,400,600,700,800';
	
	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
	);
	$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	
	return $fonts_url;
}// function


/**
 * Envoi plus de variable PHP au script map.js
 * 
 */
function add_map_data($mapData){
	$mapInfos = get_field('adresse', 'options');

	if(is_singular('artwork')){
		$mapInfos = get_field('lieu_de_loeuvre');
	}

    $mapData['gmap'] = $mapInfos;
    $mapData['childURI'] = CHILDURI;
    
    return $mapData;
}
add_filter('php_data_to_mapjs', 'add_map_data', 10, 1 );


/**
 * Envoi plus de variable PHP au script map.js
 * 
 */
function add_script_data($phpData){
    $phpData['siteURL'] = esc_url( home_url( '/' ) );
    $phpData['childURI'] = CHILDURI;
    
    return $phpData;
}
add_filter('php_data_to_scriptjs', 'add_script_data', 10, 1 );


/**
 * Ajoute une Classe pour les images flush.
 * 
 * @param String $classes
 * @hooked cdm_add_section_classes
 * 
 * @return String
 */
function add_section_image_text_flush($classes){
    if(get_sub_field('retirer_le_padding')){
        $classes .= ' no-padding';
    }

    return $classes;
}
add_filter('cdm_add_section_classes', 'add_section_image_text_flush');