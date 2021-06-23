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

 $posts_arg = array(
	 'post_type' => array( 'post' ),
	 'posts_per_page' => get_option( 'posts_per_page' ),
	 'nopaging' => false,
	 'paged' => $paged,
 );

 $context['blog_posts'] = new Timber\PostQuery( $posts_arg );

 Timber::render('templates/home-blog.twig', $context);
