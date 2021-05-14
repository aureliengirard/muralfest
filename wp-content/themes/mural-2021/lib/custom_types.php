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

 //Services
 $labels = array(
 	'name' => __('Artist'),
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
 	'menu_position' => 5,
 	'menu_icon' => 'dashicons-id',
 	'supports' => array('title', 'editor', 'thumbnail', ),
 	'rewrite' => $rewrite,
 );
 register_post_type('artist', $args);
