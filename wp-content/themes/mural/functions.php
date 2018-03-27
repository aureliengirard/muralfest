<?php

function add_google_map_key(){
    TCore()->gmapKey = '';
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
    CPT()->cptOrth = 'f'; // accorde les mots au cpt
    CPT()->register_custom_post_type('activite', array(
        'cpt_variations' => array(
            'singular' => _x('Activité', 'post type singular name', 'custom_post_type'),
            'plural' => _x('Activités', 'post type general name', 'custom_post_type')
        ),
        'args' => array(
            'menu_icon' => 'dashicons-portfolio',
            'supports' => array('title')
        )
    ));
    
    
    CPT()->cptOrth = 'm';
    CPT()->add_taxonomy('types', array(
        'custom_posts' => array('activite'),
        'tax_variations' => array(
            'singular' => _x('Type', 'taxonomy singular name', 'custom_post_type'),
            'plural' => _x('Types', 'taxonomy general name', 'custom_post_type')
        )
    ));
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