<?php
/**
 * Template Name: Carte dynamique
 *
 */


 $context = Timber::get_context();
 $post = Timber::query_post();
 $context['page'] = $post;

 $artworks = Festival()->artworks->get_artworks();
 $artworks_by_years = array();

 foreach ($artworks as $id => $artwork) {
     if(!isset($artworks_by_years[$artwork['date']])){
         $artworks_by_years[$artwork['date']] = array();
     }

     $artworks_by_years[$artwork['date']][$id] = $artwork;
 }

 $context['artworks_by_years'] = $artworks_by_years;

 // Partners
 $args = array(
     'post_type'  => 'artwork',
     'posts_per_page'  => '-1',
     'post_status' => 'publish',
     'orderby' => array(
         'title' => 'ASC',
	     'date' => 'DESC'
  	)
 );
 $context['artwork_posts'] = Timber::get_posts($args);


Timber::render('templates/page-map.twig', $context);