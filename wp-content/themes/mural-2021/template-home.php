<?php
/**
 * Template Name: Accueil
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

Timber::render('templates/home.twig', $context);
