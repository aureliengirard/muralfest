 <?php
/**
 * Template Name: Mural à l'année
 *
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
	'post_type' => array( 'over_the_year' ),
	'posts_per_page' => 10,
	'order' => 'DESC'
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

 Timber::render('templates/home-over-the-year.twig', $context);