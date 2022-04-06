 <?php
/**
 * Template Name: Programmation
 *
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

$args = array(
	'post_type' => array( 'program' ),
	'posts_per_page' => 100,
	'orderby' => array(
		'order_event' => 'ASC',
		'order_start_time' => 'ASC'
	),
	'meta_query' => array(
		'order_event' => array(
			'key' => 'event_date'
		),
		'order_start_time' => array(
			'key' => 'heure_de_debut'
		)
	)
);


$query = new WP_Query( $args );
$context['event_posts'] = new Timber\PostQuery( $args );

$current_date = NULL;
$context['current_date'] = $current_date;

global $wp_query;
// Put default query object in a temp variable
$tmp_query = $wp_query;
// Now wipe it out completely
$wp_query = null;
// Re-populate the global with our custom query
$wp_query = $query;

 Timber::render('templates/home-program.twig', $context);