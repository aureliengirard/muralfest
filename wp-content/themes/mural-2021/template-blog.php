<?php
/**
 * Template Name: Blogue
 *
 */

 $context = Timber::get_context();

 $post = new TimberPost();
 $context['post'] = $post;

 // Pagination
 global $paged;
 if (!isset($paged) || !$paged) {
   $paged = 1;
 }

$mycat = "";
if (array_key_exists('category', $_GET)) {
    $mycat = $_GET['category'];
}

 $posts_arg = array(
	 'post_type' => array( 'post' ),
	 'posts_per_page' => get_option( 'posts_per_page' ),
	 'nopaging' => false,
	 'paged' => $paged,
     'orderby', 'date',
     'order' => 'DESC',
     'category_name' => $mycat,
 );

 $context['blog_posts'] = new Timber\PostQuery( $posts_arg );

 $context['filtered_cat'] = $mycat;

 $taxonomy = array(
 	'taxonomy' => 'category',
 	'orderby' => 'term_order',
 	'order' => 'ASC'
 );
 $context['terms'] = Timber::get_terms($taxonomy);

 Timber::render('templates/home-blog.twig', $context);
