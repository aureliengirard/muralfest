<?php
/**
 * Template Name: Single Program
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;


$current_url_without_params = get_permalink();
$themeURI = get_template_directory_uri();
$homeURL = esc_url( home_url( '/' ) );
$gmap = get_field('adresse', 'options');
$mapInfos = get_field('lieu_de_loeuvre');
$mapData_year = get_field('annee');
$mapData_gmap = $mapInfos;


$context['gmap'] = $gmap;
$context['homeURL'] = $homeURL;
$context['themeURI'] = $themeURI;
$context['current_url_without_params'] = $current_url_without_params;
$context['mapData_gmap'] = $mapData_gmap;
$context['mapData_year'] = $mapData_year;

Timber::render('single-artwork.twig', $context);