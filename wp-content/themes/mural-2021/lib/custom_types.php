<?php

// CUSTOM POST TYPES DECLARATION.
// META BOXES FOR PARENT RELATIONSHIPS ARE AT THE BOTTOM

// Services
// $labels = array(
// 	'name' => __('Services'),
// 	'singular_name' => __('Service'),
// 	'add_new_item' => __('Add New Service'),
// 	'new_item' => __('New Service'),
// );
// $rewrite = array(
// 	'slug' => 'service',
// 	'with_front' => true,
// 	'pages' => true,
// );
// $args = array(
// 	'labels' => $labels,
// 	'public' => true,
// 	'has_archive' => false,
// 	'show_in_menu' => true,
// 	'hierarchical' => false,
// 	'menu_position' => 5,
// 	'menu_icon' => 'dashicons-hammer',
// 	'supports' => array('title', 'editor', 'thumbnail', ),
// 	'rewrite' => $rewrite,
// );
// register_post_type('service', $args);

 //Artwork
 $labels = array(
 	'name' => __('Œuvres'),
 	'singular_name' => __('Œuvre'),
 	'plural_name' => __('Œuvres'),
 	'add_new_item' => __('Ajouter une œuvre'),
 	'new_item' => __('Nouvelle œuvre'),
 );
 $rewrite = array(
 	'slug' => 'artwork',
 	'with_front' => true,
 	'pages' => true,
 );
 $args = array(
 	'labels' => $labels,
 	'public' => true,
 	'has_archive' => false,
 	'show_in_menu' => true,
 	'hierarchical' => false,
 	'menu_position' => 3,
 	'menu_icon' => 'dashicons-art',
 	'supports' => array('title', 'editor' ),
 	'rewrite' => $rewrite,
 );
 register_post_type('artwork', $args);

 //Artistes
 $labels = array(
 	'name' => __('Artistes'),
 	'singular_name' => __('Artiste'),
 	'plural_name' => __('Artistes'),
 	'add_new_item' => __('Ajouter un artiste'),
 	'new_item' => __('Nouvel artiste'),
 );
 $rewrite = array(
 	'slug' => 'artist',
 	'with_front' => true,
 	'pages' => true,
 );
 $args = array(
 	'labels' => $labels,
 	'public' => true,
 	'has_archive' => false,
 	'show_in_menu' => true,
 	'hierarchical' => false,
 	'menu_position' => 4,
 	'menu_icon' => 'dashicons-id',
 	'supports' => array('title', 'editor', 'thumbnail', ),
 	'rewrite' => $rewrite,
 );
 register_post_type('artist', $args);

 // Partner
 $labels = array(
 	'name' => __('Partenaires'),
 	'singular_name' => __('Partenaire'),
 	'plural_name' => __('Partenaires'),
 	'add_new_item' => __('Ajouter un partenaire'),
 	'new_item' => __('Nouveau partenaire'),
 );
 $rewrite = array(
 	'slug' => 'partner',
 	'with_front' => true,
 	'pages' => true,
 );
 $args = array(
 	'labels' => $labels,
 	'public' => true,
 	'has_archive' => false,
 	'show_in_menu' => true,
 	'hierarchical' => false,
 	'menu_position' => 7,
 	'menu_icon' => 'dashicons-groups',
    'publicly_queryable'  => false,
    'exclude_from_search' => true,
 	'supports' => array('title'),
 	'rewrite' => $rewrite,
 );
 register_post_type('partner', $args);

 // Program
 $labels = array(
 	'name' => __('Évènements'),
 	'singular_name' => __('Évènement'),
 	'plural_name' => __('Évènements'),
 	'add_new_item' => __('Ajouter un évènement'),
 	'new_item' => __('Nouvel évènement'),
 );
 $rewrite = array(
 	'slug' => 'program',
 	'with_front' => true,
 	'pages' => true,
 );
 $args = array(
 	'labels' => $labels,
 	'public' => true,
 	'has_archive' => false,
 	'show_in_menu' => true,
 	'hierarchical' => false,
 	'menu_position' => 5,
    'menu_icon' => 'dashicons-tickets-alt',
    'supports' => array('title', 'editor'),
 	'rewrite' => $rewrite,
 );
 register_post_type('program', $args);

 // Mural à l'année
 $labels = array(
 	'name' => __('Évènements hors festival'),
 	'singular_name' => __('Évènement'),
 	'plural_name' => __('Évènements'),
 	'add_new_item' => __('Ajouter un évènement'),
 	'new_item' => __('Nouvel évènement'),
 );
 $rewrite = array(
 	'slug' => 'mural-year-long',
 	'with_front' => true,
 	'pages' => true,
 );
 $args = array(
 	'labels' => $labels,
 	'public' => true,
 	'has_archive' => false,
 	'show_in_menu' => true,
 	'hierarchical' => false,
 	'menu_position' => 6,
    'menu_icon' => 'dashicons-location-alt',
    'supports' => array('title', 'editor'),
    'publicly_queryable'  => true,
    'exclude_from_search' => true,
 	'rewrite' => $rewrite,
 );
 register_post_type('over_the_year', $args);

 // Venue
 $labels = array(
 	'name' => __('Lieux'),
 	'singular_name' => __('Lieu'),
 	'plural_name' => __('Lieux'),
 	'add_new_item' => __('Ajouter un lieu'),
 	'new_item' => __('Nouveau lieu'),
 );
 $rewrite = array(
 	'slug' => 'venue',
 	'with_front' => true,
 	'pages' => true,
 );
 $args = array(
 	'labels' => $labels,
 	'public' => true,
 	'has_archive' => false,
 	'show_in_menu' => true,
 	'hierarchical' => false,
 	'menu_position' => 8,
    'menu_icon' => 'dashicons-location-alt',
    'supports' => array('title', 'editor'),
    'publicly_queryable'  => false,
    'exclude_from_search' => true,
 	'rewrite' => $rewrite,
 );
 register_post_type('venue', $args);