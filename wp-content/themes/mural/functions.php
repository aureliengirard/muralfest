<?php
include_once('inc/class-Festival.php');

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



/*********
/* Register script and style in relation with the parent theme
*********/
function theme_enqueue_styles() {
	/* enqueue script */
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
	wp_enqueue_style( 'child-style',
		CHILDURI . '/style.css',
		null,
		wp_get_theme()->get('Version')
	);
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 11 );



/**
 * Return the Google font stylesheet URL, if available.
 *
*/
function theme_fonts_url() {
	$fonts_url = '';
	
	$font_families = array();
	
	$font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';  // Basic
	$font_families[] = 'Bitter:400,700'; // Basic
	
	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
	);
	$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	
	return $fonts_url;
}// function