<?php
/**
 * Template Name: Accueil
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

// post
$args = array(
    'post_type'  => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish'
);
$context['blog_posts'] = new Timber\PostQuery($args);
$context['blog_posts_link'] = get_post_type_archive_link('post');

Timber::render('templates/home.twig', $context);
