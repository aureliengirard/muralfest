<?php

 // Partner Tier
$labels = array(
    'name'              => _x( 'Niveaux', 'taxonomy general name' ),
    'singular_name'     => _x( 'Niveau', 'taxonomy singular name' ),
    'search_items'      => __( 'Search a Tier' ),
    'all_items'         => __( 'All Tiers' ),
    'parent_item'       => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item'         => __( 'Mettre à jour le niveau' ),
    'update_item'       => __( 'Mettre à jour' ),
    'add_new_item'      => __( 'Ajouter un niveau' ),
    'new_item_name'     => __( 'Nouveau niveau' ),
    'menu_name'         => __( 'Niveau' ),
    'not_found'         => __( 'No Tiers Found' ),
);

$args = array(
    'hierarchical'      => true,
    'has_archive'       => true,
    'labels'            => $labels,
    'public'            => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array(
        'slug'          => 'product-categories',
        'with_front'    => true,
        'pages'         => true,
    ),
);
register_taxonomy( 'tier', array( 'partner', ), $args );

 // Event Catogory
$labels = array(
    'name'              => _x( 'Catégories', 'taxonomy general name' ),
    'singular_name'     => _x( 'Catégorie', 'taxonomy singular name' ),
    'search_items'      => __( 'Search a Category' ),
    'all_items'         => __( 'Toutes les catégories' ),
    'parent_item'       => __( 'Catégorie parent' ),
    'parent_item_colon' => __( 'Catégorie parent:' ),
    'edit_item'         => __( 'Mettre à jour la catégorie' ),
    'update_item'       => __( 'Mettre à jour' ),
    'add_new_item'      => __( 'Ajouter une categorie' ),
    'new_item_name'     => __( 'Nouvelle categorie' ),
    'menu_name'         => __( 'Catégorie' ),
    'not_found'         => __( 'No Categories Found' ),
);

$args = array(
    'hierarchical'      => true,
    'has_archive'       => true,
    'labels'            => $labels,
    'public'            => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array(
        'slug'          => 'event-category',
        'with_front'    => true,
        'pages'         => true,
    ),
);
register_taxonomy( 'event-category', array( 'program', ), $args );

 // Artist Style
$labels = array(
    'plural_name'       => _x( 'Tags', 'taxonomy general name' ),
    'singular_name'     => _x( 'Tag', 'taxonomy singular name' ),
    'search_items'      => __( 'Search a style' ),
    'all_items'         => __( 'Toutes les styles' ),
    'parent_item'       => __( 'Style parent' ),
    'parent_item_colon' => __( 'Style parent:' ),
    'edit_item'         => __( 'Mettre à jour le style' ),
    'update_item'       => __( 'Mettre à jour' ),
    'add_new_item'      => __( 'Ajouter un style' ),
    'new_item_name'     => __( 'Nouveau style' ),
    'menu_name'         => __( 'Style' ),
    'not_found'         => __( 'Style Found' ),
);

$args = array(
    'hierarchical'      => true,
    'has_archive'       => true,
    'labels'            => $labels,
    'public'            => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array(
        'slug'          => 'style',
        'with_front'    => true,
        'pages'         => true,
    ),
);
register_taxonomy( 'style', array( 'artist', ), $args );

 // Artist Style
$labels = array(
    'name'              => _x( 'Tag', 'taxonomy general name' ),
    'singular_name'     => _x( 'Tag', 'taxonomy singular name' ),
    'search_items'      => __( 'Search a tag' ),
    'all_items'         => __( 'Toutes les tags' ),
    'parent_item'       => __( 'Tag parent' ),
    'parent_item_colon' => __( 'Tag parent:' ),
    'edit_item'         => __( 'Mettre à jour le tag' ),
    'update_item'       => __( 'Mettre à jour' ),
    'add_new_item'      => __( 'Ajouter un tag' ),
    'new_item_name'     => __( 'Nouveau style' ),
    'menu_name'         => __( 'Style' ),
    'not_found'         => __( 'Style Found' ),
);

$args = array(
    'hierarchical'      => true,
    'has_archive'       => true,
    'labels'            => $labels,
    'public'            => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array(
        'slug'          => 'tag-venue',
        'with_front'    => true,
        'pages'         => true,
    ),
);
register_taxonomy( 'tag-venue', array( 'venue', ), $args );