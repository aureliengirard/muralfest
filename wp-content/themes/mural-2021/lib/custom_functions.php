<?php

function get_breadcrumb($parent_taxonomy_label=false, $parent_taxonomy_link=false, $child_taxonomy_label=false, $child_taxonomy_link=false) {

    $link_blog = get_field('home_blog', 'options');
    $link_artwork = get_field('home_artork', 'options');
    $link_artist = get_field('home_artist', 'options');
    $link_partner = get_field('home_partners', 'options');
    $link_program = get_field('home_program', 'options');
    $link_over_the_year = get_field('home_over_the_year', 'options');

    $divider = " / ";

    // global $post;
    global $wp_query;
    $queried_object = $wp_query->get_queried_object();

    echo "<a title='" . __('Home', 'site-theme') . "' href='" . get_home_url() . "'>" . __('Home', 'site-theme') . "</a>";

    if (is_category() || is_single() || is_tax()) {
        echo $divider;

        if ( get_post_type() == 'artwork' ){

            echo "<a href='" . $link_artwork . " '>" . __('Dynamic Map', 'site-theme') . " </a>" ;

            echo $divider;

        } elseif ( get_post_type() == 'artist' ) {

            echo "<a href='" . $link_artist . " '>" . __('Artists', 'site-theme') . " </a>" ;

            echo $divider;

        } elseif ( get_post_type() == 'program' ) {

            echo "<a href='" . $link_program . " '>" . __('Program', 'site-theme') . " </a>" ;

            echo $divider;

        } elseif ( get_post_type() == 'over_the_year' ) {

            echo "<a href='" . $link_over_the_year . " '>" . __('Mural year-round', 'site-theme') . " </a>" ;

            echo $divider;

        } elseif ( is_single() ){

            echo "<a href='" . $link_blog . " '>" . __('Blog', 'site-theme') . "</a>" ;

            echo $divider;
        }

        if ($parent_taxonomy_label && $parent_taxonomy_link) {
            echo "<a href='". $parent_taxonomy_link . "'>" . $parent_taxonomy_label . "</a>" ;

            echo $divider;
        }

        if ($child_taxonomy_label && $child_taxonomy_link) {
            echo "<a href='". $child_taxonomy_link . "'>" . $child_taxonomy_label . "</a>" ;

            echo $divider;
        }

        if(is_single()) {
            $post = $queried_object;
            $text = $post->post_title;
            $chars = 45;

            if (strlen($text) <= $chars) {
                return $text;
            }
            $text = $text." ";
            $text = substr($text,0,$chars);
            $text = substr($text,0,strrpos($text,' '));
            $text = $text."...";

            echo "<span class='active'>" . $text . "</span>";

        } elseif(is_tax()) {
            $taxonomy = $queried_object;
            echo "<span class='active'>" . $taxonomy->name . "</span>";
        } elseif(is_category()) {
            $category = $queried_object;
            echo "<span class='active'>" . $category->name . "</span>";
        }

    } elseif (is_home()) {
            echo $divider;
            echo "<span class='active'>" . $blog_home . "</span>";

    } elseif (is_page()) {
        $post = $queried_object;
        if($post->post_parent){
            $anc = get_post_ancestors( $post->ID );
            $anc_link = get_page_link( $post->post_parent );
            $anc_title = get_the_title( $post->post_parent );

            foreach ( $anc as $ancestor ) {
                $output = $divider."<a title='".$anc_title."' href=".$anc_link.">".get_the_title($ancestor)."</a>".$divider;
            }

            echo $output;
            echo "<span title='" . get_the_title() . "' class='active'>" . get_the_title() . "</span>";

        } else {
            echo $divider;
            echo "<span title='" . get_the_title() . "' class='active'>" . get_the_title() . "</span>";
        }
    }
}


//BACK BUTTON
function get_back_button(){
	$url = wp_get_referer();

	if(!$url){
		if(is_single()){
			$url = get_the_permalink(get_posttype_parent_id());

		}else if($parent_id = wp_get_post_parent_id(get_the_ID())){
			$url = get_the_permalink($parent_id);
		}
	}

	$other_lang = (ICL_LANGUAGE_CODE == 'fr' ? 'en' : 'fr');

	$other_lang_url = apply_filters( 'wpml_permalink', get_the_permalink(), $other_lang );

    echo '<div class="col-md-3">';
	if($url && $url != $other_lang_url){
		echo '<a class="readmore back-btn" href="'. $url .'">< '. __('Back', 'custom_theme') .'</a>';
	}
    echo '</div>';
}

function language_selector($display = 'native_name'){
    if(function_exists('icl_get_languages')){
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if(!empty($languages)){
            foreach($languages as $l){
                if(!$l['active']) {
                    echo '<li><a href="'.$l['url'].'" class="lang"> ';
                    echo $l[$display];
                    echo '</a></li>';
                }
            }
        }
    }
}

if(!function_exists('language_selector')){
	/**
	 * Affiche un sélecteur de langue utilisant WPML.
	 *
	 * @var String $display Type d'affichage des langues. (ex: full name, code, flag, etc.)
	 */
}

function get_current_language() {
    $lang = ICL_LANGUAGE_CODE;
    echo $lang;
}