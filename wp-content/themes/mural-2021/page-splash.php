<?php
/**
 * Template Name: Splash Page
 */

$context = Timber::get_context();

$post = new TimberPost();
$context['page'] = $post;

Timber::render('templates/page-splash.twig', $context);
