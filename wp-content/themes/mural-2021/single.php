<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

$category_obj = get_the_category();
$categories = array();
foreach($category_obj as $categorie){
	$categories[] = $categorie->cat_ID;

}
$args = array(
	'post_type' => array( 'post' ),
	'posts_per_page' => 3,
	'category__in' => $categories,
	'orderby'	=> 'rand',
);
$args['post__not_in'] = array(get_the_ID());

$context['blog_articles'] = new Timber\PostQuery( $args );

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );
}
