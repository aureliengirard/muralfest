<?php
/**
 * Template Name: Artistes
 *
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

// Filter elements
$year_value = '';
if (isset($_GET['years'])) {
    $year_value = sanitize_text_field ($_GET['years']);
}
$context['year_value'] = $year_value;

$years = get_field_object('field_5aeb0f481de34');
$years = $years['choices'];
$context['years'] = $years;

// Posts elements
 global $place_holder_artist;
 $place_holder_artist = get_field ("artwork_placeholder");

 $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
 $args = array(
 	'post_type' => array( 'artist' ),
 	'posts_per_page' => 20,
 	'nopaging' => false,
 	'paged' => $paged,
 	'orderby' => array(
 		'years' => 'DESC',
 		'title' => 'ASC'
 	),
 	'meta_query' => array(
 		'years' => array(
 			'key' => 'annee'
 		)
 	)
 );

 if (isset($_GET['years']) && $_GET['years'] != '') {
 	$args['meta_query']['years']['value'] = sanitize_text_field($_GET['years']);
 }

 $query = new WP_Query( $args );
 $context['artist_posts'] = new Timber\PostQuery( $args );

// Artworks
$args = array(
	'post_type'  => 'artwork',
	'posts_per_page'  => '-1',
	'post_status' => 'publish',
	'orderby' => array(
		'title' => 'ASC',
		'date' => 'DESC'
	 )
);
$context['artwork_posts'] = Timber::get_posts($args);
 global $wp_query;

 // Put default query object in a temp variable
 $tmp_query = $wp_query;
 // Now wipe it out completely
 $wp_query = null;
 // Re-populate the global with our custom query
 $wp_query = $query;

 $current_year = NULL;
 $context['current_year'] = $current_year;

$place_holder_artist = get_field ("artwork_placeholder");
$context['place_holder_artist'] = $place_holder_artist;

 Timber::render('templates/home-artists.twig', $context);