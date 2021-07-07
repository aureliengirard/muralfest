<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = array( 'archive.twig', 'index.twig' );

$context = Timber::get_context();

$context['posts'] = new Timber\PostQuery();

$taxonomy = array(
   'taxonomy' => 'category',
   'orderby' => 'term_order',
   'order' => 'ASC',
   'hide_empty' => true
);
$context['terms'] = Timber::get_terms($taxonomy);

Timber::render('home-blog.twig', $context);
