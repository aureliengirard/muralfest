<?php
/**
 * Template Name: Media/Press
 *
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

Timber::render('templates/page-media.twig', $context);