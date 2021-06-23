 <?php
/**
 * Template Name: Programmation
 *
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['page'] = $post;

$taxonomy = array(
	'taxonomy' => 'event-category',
	'orderby' => 'term_order',
	'order' => 'ASC'
);
$context['terms'] = Timber::get_terms($taxonomy);

$date_value = '';
if(isset($_GET['date'])){
    $date_value = $_GET['date'];
}
$context['date_value'] = $date_value;

$artist_value = '';
if(isset($_GET['filtre-artiste'])){
    $artist_value = sanitize_text_field($_GET['filtre-artiste']);

}
$context['artist_value'] = $artist_value;

$caterogy_value = '';
if (isset($_GET['category'])) {
    $caterogy_value = sanitize_text_field ($_GET['category']);
}

$context['caterogy_value'] = $caterogy_value;


$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
	'post_type' => array( 'program' ),
	'posts_per_page' => 9,
	'nopaging' => false,
	'paged' => $paged,
	'orderby' => array(
		'order_event' => 'ASC',
		'order_start_time' => 'ASC'
	),
	'meta_query' => array(
		'order_event' => array(
			'key' => 'event_date'
		),
		'order_start_time' => array(
			'key' => 'heure_de_debut'
		)
	)
);

if(isset($_GET['filtre-artiste']) && $_GET['filtre-artiste'] != ''){
	$artist_arg= [
		'post_type' => 'artist',
		'posts_per_page' => 1,
		'post_name__in' => array(sanitize_text_field($_GET['filtre-artiste'])),
		'fields' => 'ids'
	];
	$q = get_posts($artist_arg);
	$args['meta_query'][] = array(
		'key' => 'artiste',
		'value' => serialize(strval($q[0])),
		'compare' => 'LIKE'
	);
}


if (isset($_GET['category']) && $_GET['category'] != '') {
$args['tax_query'][] = array(
	'taxonomy' => 'event-category',
	'field' => 'slug',
	'terms' => sanitize_text_field($_GET['category']),
);
}


if(isset($_GET['date']) && $_GET['date'] != ''){
	$posted_date = $_GET['date'];

	if(strrpos($posted_date, ' - ')){
		$daterange = explode(' - ', $posted_date);

		$start_date = DateTime::createFromFormat('d/m/Y', $daterange[0]);
		$end_date = DateTime::createFromFormat('d/m/Y', $daterange[1]);

		$args['meta_query'][] = array(
			array(
				'key' => 'event_date',
				'type' => 'DATE',
				'value' => $end_date->format('Ymd'),
				'compare' => '<='
			),
			array(
				'key' => 'date_de_fin',
				'type' => 'DATE',
				'value' => $start_date->format('Ymd'),
				'compare' => '>='
			)
		);

	}else{
		$date = DateTime::createFromFormat('d/m/Y', $posted_date);

		$args['meta_query'][] = array(
			array(
				'key' => 'event_date',
				'type' => 'DATE',
				'value' => $date->format('Ymd'),
				'compare' => '<='
			),
			array(
				'key' => 'date_de_fin',
				'type' => 'DATE',
				'value' => $date->format('Ymd'),
				'compare' => '>='
			)
		);
	}
}

$query = new WP_Query( $args );
$context['event_posts'] = new Timber\PostQuery( $args );

global $wp_query;
// Put default query object in a temp variable
$tmp_query = $wp_query;
// Now wipe it out completely
$wp_query = null;
// Re-populate the global with our custom query
$wp_query = $query;

 Timber::render('templates/home-program.twig', $context);