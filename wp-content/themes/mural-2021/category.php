<?php
/**
 * Template Name: (Blog) Post Categories Index
 */

$context = Timber::get_context();

global $paged;
if (!isset($paged) || !$paged) {
	$paged = 1;
}

$context['cat_title'] = single_cat_title( '', false );
$context['is_category'] = true;
$context['categories'] = Timber::get_terms('category');

$context['blog_posts'] = new Timber\PostQuery();

Timber::render('templates/home-blog.twig', $context);
