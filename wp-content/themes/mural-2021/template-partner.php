<?php
/**
 * Template Name: Partenaire
 *
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

// Partners
$args = array(
    'post_type'  => 'partner',
    'posts_per_page'  => '-1',
    'post_status' => 'publish'
);
$context['partner_posts'] = Timber::get_posts($args);

$tiers = array(
    'taxonomy' => 'tier',
    'orderby' => 'term_order',
    'order' => 'ASC'
);
$context['tiers'] = Timber::get_terms($tiers);

Timber::render('templates/partner.twig', $context);
