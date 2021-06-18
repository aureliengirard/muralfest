<?php

/**
 * Theme setup.
 * This is the basic Wordpress theme setup.
 *
 * @hooked after_setup_theme
 */
function theme_setup() {
	/*
	 * Makes it available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'custom_theme', get_template_directory() . '/languages' );
	
	
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'custom_theme' ) );
	
	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'theme_setup' );



if( !function_exists('theme_fonts_url') ){

/**
 * Function vide, sert seulement au developpement de codems blank
 */
	function theme_fonts_url() {
		$fonts_url = '';
		
		return $fonts_url;
	}// function

}


require_once('inc/basic-core.php');





///////////////
/////  CUSTOM
///////////////

include_once('inc/core.php');


