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


Timber::render('templates/page-map.twig', $context);