<?php
/**
 * Template Name: Single Program
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

Timber::render('single-artwork.twig', $context);
