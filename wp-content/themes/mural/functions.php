<?php
include_once('inc/mural-api.php');
include_once('inc/class-Festival.php');
include_once('inc/tinymce_formats.php');

function add_google_map_key(){
    TCore()->gmapKey = 'AIzaSyCF3WxcpgQ24K5pFkWR8en9WMyEpkY4JwQ';
}
add_action( 'TCore_init', 'add_google_map_key'); // on TCore init


function child_theme_setup() {
	/*
	 * Makes it available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'site-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'child_theme_setup' );


/* Is called when TCore is ready */
function on_TCore_ready(){
    TCore()->define('CHILDURI', get_stylesheet_directory_uri());
}
add_action( 'TCore_ready', 'on_TCore_ready');



function register_CPT(){
    
}
//add_action( 'CPT-ready', 'register_CPT');


/**
 * Register image sizes
 * 
 * @hooked init
 */
function cdm_add_images_sizes(){
    add_image_size( 'cta-preview', 400, 285, array('center', 'center') );
	add_image_size( 'blog-preview', 600, 400, array('center', 'center') );
	add_image_size( 'api-event', 1750, 1000, array('center', 'center') );
	add_image_size( 'single-program', 1170 );
}
add_action( 'init', 'cdm_add_images_sizes' );


/*********
/* Register script and style in relation with the parent theme
*********/
function theme_enqueue_styles() {
	/* enqueue script */
	wp_enqueue_script("jqueryui-js",
		"//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js",
		array('jquery'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("select2",
		"//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js",
		array('jquery'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("datepicker-fr",
		CHILDURI."/js/datepicker-fr-CA.js",
		array('jqueryui-js'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("daterange",
		CHILDURI."/js/daterange-calendar.js",
		array('jqueryui-js'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("colorbox",
		CHILDURI."/js/jquery.colorbox-min.js",
		array('jquery'),
		wp_get_theme()->get('Version'),
		true
	);

	wp_enqueue_script("map",
		CHILDURI."/js/map.js",
		array('theme-utils'),
		wp_get_theme()->get('Version'),
		true
	);
	wp_enqueue_script("artworks-map", CHILDURI."/js/map-arts.js", array('jquery'), '1.0.0', true);
	
	wp_enqueue_script("script",
		CHILDURI."/js/script.js",
		array('theme-utils'),
		wp_get_theme()->get('Version'),
		true
	);
	
	/* enqueue style */
	wp_enqueue_style( 'select2-style',
		'//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css',
		null,
		wp_get_theme()->get('Version')
	);

	wp_enqueue_style( 'jqueryui-style',
		'//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css',
		null,
		wp_get_theme()->get('Version')
	);

	wp_enqueue_style( 'child-style',
		CHILDURI . '/style.css',
		null,
		wp_get_theme()->get('Version')
	);
	wp_enqueue_style( 'animate',
		CHILDURI . '/scss/animate.css',
		null,
        wp_get_theme()->get('Version')
	);
	
	// full Calendar ( program schedule )
	wp_enqueue_script("fullcalendar-core",
		CHILDURI."/inc/fullcalendar/js/core.main.min.js",
		wp_get_theme()->get('Version'),
		true
	);
	wp_enqueue_script("fullcalendar-daygrid",
		CHILDURI."/inc/fullcalendar/js/daygrid.main.min.js",
		wp_get_theme()->get('Version'),
		true
	);
	wp_enqueue_style( 'fullcalendar-main',
	CHILDURI."/inc/fullcalendar/css/main.min.css",
		null,
		wp_get_theme()->get('Version')
	);
	wp_enqueue_style( 'fullcalendar-daygrid',
	CHILDURI."/inc/fullcalendar/css/daygrid.main.min.css",
		null,
		wp_get_theme()->get('Version')
	);
	wp_dequeue_style('font-awesome-4');
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 11 );

function add_twitter_widget(){
	?>
	<script>window.twttr = (function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0],
		  t = window.twttr || {};
		if (d.getElementById(id)) return t;
		js = d.createElement(s);
		js.id = id;
		js.src = "https://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js, fjs);
	  
		t._e = [];
		t.ready = function(f) {
		  t._e.push(f);
		};
	  
		return t;
	  }(document, "script", "twitter-wjs"));</script>
	  <?php
}
add_action('wp_footer','add_twitter_widget',100);

/**
 * Return the Google font stylesheet URL, if available.
 *
*/
function theme_fonts_url() {
	$fonts_url = '';
	
	$font_families = array();
	
	$font_families[] = 'Open Sans:300,400,600,700,800';
	$font_families[] = 'Cardo:400,400i,700';
	
	$query_args = array(
		'family' => urlencode( implode( '|', $font_families ) ),
		'subset' => urlencode( 'latin,latin-ext' ),
	);
	$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	
	return $fonts_url;
}// function


/**
 * Envoi plus de variable PHP au script map.js
 * 
 */
function add_map_data($mapData){
	$mapInfos = get_field('adresse', 'options');
	
	if(is_singular('artwork')){
		$mapInfos = get_field('lieu_de_loeuvre');
		$mapData['year'] = get_field('annee');
	}
	
    $mapData['gmap'] = $mapInfos;
    $mapData['childURI'] = CHILDURI;
    
    return $mapData;
}
add_filter('php_data_to_mapjs', 'add_map_data', 10, 1 );


/**
 * Envoi plus de variable PHP au script map.js
 * 
 */
function add_script_data($phpData){
    $phpData['siteURL'] = esc_url( home_url( '/' ) );
	$phpData['childURI'] = CHILDURI;
    
    return $phpData;
}
add_filter('php_data_to_scriptjs', 'add_script_data', 10, 1 );


/**
 * Ajoute une Classe pour les images flush.
 * 
 * @param String $classes
 * @hooked cdm_add_section_classes
 * 
 * @return String
 */
function add_section_image_text_flush($classes){
    if(get_sub_field('retirer_le_padding')){
        $classes .= ' no-padding';
    }

    return $classes;
}
add_filter('cdm_add_section_classes', 'add_section_image_text_flush');


/**
 * Envoie les informations requise pour le calendrier
 * 
 */
function send_date_to_calendar(){
	$minmax = array(
		'min' => '',
		'max' => ''
	);
	$args = array(
		'post_type' => array( 'program' ),
		'posts_per_page' => -1,
		'orderby' => array(
			'order_event' => 'ASC',
		),
		'meta_query' => array(
			'order_event' => array(
				'key' => 'event_date'
			)
		)
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ){
		while ( $query->have_posts() ){
			$query->the_post();

			$event_start = strtotime(get_field('event_date'));
			$event_end = strtotime(get_field('date_de_fin'));

			if($event_start < $minmax['min'] || !$minmax['min']){
				$minmax['min'] = $event_start;
			}

			if($event_end > $minmax['max'] || !$minmax['max']){
				$minmax['max'] = $event_end;
			}
		}

		wp_reset_postdata();

		$minmax['min'] = array(
			'year' => date('Y', $minmax['min']),
			'month' => date('m', $minmax['min']),
			'day' => date('d', $minmax['min'])
		);

		$minmax['max'] = array(
			'year' => date('Y', $minmax['max']),
			'month' => date('m', $minmax['max']),
			'day' => date('d', $minmax['max'])
		);
	}

	wp_localize_script( 'daterange', 'datelimit', $minmax);

	wp_localize_script( 'daterange', 'translation', array(
		'reset' => __('Reset', 'site-theme'),
		'done' => __('Done', 'site-theme')
	));
}
add_action( 'wp_enqueue_scripts', 'send_date_to_calendar', 30 );


/**
 * ajoute les pages parentes des singles
 * 
 */
function custom_post_type_breadcrumb($separator){
    $post_id = get_the_ID();
	$page_parent = NULL;
	
	$parent_id = get_posttype_parent_id();
	
	if($parent_id)
		$page_parent = get_post(get_posttype_parent_id());
    
    if($page_parent){
        if($page_parent->post_parent)
            echo '<a href="'.get_permalink($page_parent->post_parent).'">'.get_the_title($page_parent->post_parent).'</a> '.$separator.' ';
        
        echo '<a href="'.get_permalink($page_parent->ID).'">'.get_the_title($page_parent->ID).'</a> '.$separator.' ';
    }
}
add_action( 'breadcrumb_single_parents', 'custom_post_type_breadcrumb', 10, 1);


/**
 * Retourne le ID de la page associé au CPT.
 */
function get_posttype_parent_id($post_type = NULL){
    $page_id = false;
    
    if(!$post_type)
        $post_type = get_post_type();
    
    
    if ( have_rows( 'liaison_cpt_pages', 'options' ) ){
        while ( have_rows( 'liaison_cpt_pages', 'options' ) ){ the_row();
            if(get_sub_field( 'article_personnalise' ) == $post_type)
                $page_id = get_sub_field( 'page' );
        }
    }
    
    return $page_id;
}


/**
 * Retourne les images de tous les oeuvres d'un artiste.
 * 
 * @param Int $artist_id
 * @return Array
 */
function get_artist_artworks_images($artist_id){
	$artwork_args = array(
        'post_type' => array('artwork'),
        'posts_per_page' => -1,
        'meta_key' => 'artiste',
        'meta_value' => $artist_id,
        'orderby' => 'annee',
        'order' => 'ASC'
    );

	$artwork_query =  new WP_Query($artwork_args);

	$artworks_images = array();

	if($artwork_query->have_posts()){
		while ($artwork_query->have_posts()){
			$artwork_query->the_post();

			if(get_field('image_de_loeuvre')){
				$artworks_images[] = array(
					'id' => get_the_ID(),
					'year' => get_field('annee'),
					'image_id' => get_field('image_de_loeuvre')
				);
			}

		}
	}

	wp_reset_postdata();
	
	return $artworks_images;
}


/**
 * Affiche un bouton retour
 */
function display_back_button(){
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

	if($url && $url != $other_lang_url){
		echo '<a class="readmore back-btn" href="'. $url .'">< '. __('Back', 'custom_theme') .'</a>';
	}
}



/**
 * Permet de changer le nom de la section.
 * 
 * @param String $label
 * @param Array $field
 * @hooked acf/get_field_label
 * @return String
 */
function name_row_section( $label, $field ){
	if($field['key'] == 'field_5ad74efd915d7'){
		$parent = get_field($field['parent']);
		$row_number = str_replace('acf['.$field['parent'].']', '', $field['prefix']);
		$row_number = str_replace(array('[', ']'), '', $row_number);
		
		if(array_key_exists($row_number, $parent) && $parent[$row_number]['nom_de_la_section']){
			$label = $parent[$row_number]['nom_de_la_section'];
		}
	}
	
	return $label;
}
add_filter('acf/get_field_label', 'name_row_section', 10, 2 );