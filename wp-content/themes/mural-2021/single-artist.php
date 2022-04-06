<?php
/**
 * Template Name: Single Artist
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;


$artist_id = icl_object_id(get_the_ID(), 'artist', false, "fr");
$artist_artwork = false;
$image_id = '';

if(get_field('image_de_lartiste', $artist_id)){
    $image_id = get_field('image_de_lartiste', $artist_id);
}else{
    $artist_artworks_img = get_artist_artworks_images($artist_id);
    if(!empty($artist_artworks_img)){
        $artist_artwork = true;
        $image_id = $artist_artworks_img[0]['image_id']; // 0 pour la dernière oeuvre
    }
}

$context['image_id'] = $image_id;
$context['artist_artwork'] = $artist_artwork;
$context['artist_artwork_id'] = new Timber\Image($image_id);


$acf_country = new acf_country_helpers();
$country_list_en = $acf_country->get_countries('en_CA');
$country_en = $country_list_en[get_field('pays_dorigine')];

$country_list_fr = $acf_country->get_countries('fr_CA');
$country_fr = $country_list_fr[get_field('pays_dorigine')];

$context['country'] = get_field('pays_dorigine');
$context['country_en'] = $country_en;
$context['country_fr'] = $country_fr;

Timber::render('single-artist.twig', $context);
