<?php

if(!function_exists('theme_scripts_styles')){
	/**
	 * Enregistre des scripts et css de twentythirteen pour le frontend.
	 * 
	 * @TODO Trier les fichiers pour avoir seulement ceux qu'on a de besoin.
	 * @hooked wp_enqueue_scripts
	 */
	function theme_scripts_styles() {
	
		//wp_enqueue_script( 'jquery-masonry' );

		// Loads JavaScript file with functionality specific to Twenty Thirteen.
		wp_enqueue_script( 'twentythirteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '2014-03-18', true );

		// Add Source Sans Pro and Bitter fonts, used in the main stylesheet.
		wp_enqueue_style( 'google-fonts', theme_fonts_url(), array(), null );

		// Add Genericons font, used in the main stylesheet.
		wp_enqueue_style( 'genericons', get_template_directory_uri() . '/fonts/genericons.css', array(), '2.09' );

		// Loads our main stylesheet.
		wp_enqueue_style( 'structure-style', get_template_directory_uri(). '/style.css', array(), '20.0' );

		// Loads the Internet Explorer specific stylesheet.
		wp_enqueue_style( 'twentythirteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentythirteen-style' ), '20.0' );
		wp_style_add_data( 'twentythirteen-ie', 'conditional', 'lt IE 9' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_scripts_styles' );


/**
 * Pour cacher les options qu'on ne veut pas dans le customizer
 *
 * @param string $wp_customize 
 * @return void
 * @author François Lavigne
 * @hooked customize_register
 */
function remove_customizer_settings( $wp_customize ){
	//$wp_customize->remove_section('title_tagline');
	//$wp_customize->remove_section('header_image');
	//$wp_customize->remove_section('background_image');
	//$wp_customize->remove_section('nav');
	//$wp_customize->remove_section('static_front_page');
	//$wp_customize->remove_section('custom_css');
	//$wp_customize->get_panel( 'nav_menus' )->active_callback = '__return_false';
	$wp_customize->get_panel( 'themes' )->active_callback = '__return_false'; // Le bouton pour changer de thème
	
	
}
add_action( 'customize_register', 'remove_customizer_settings', 20 );


if(!function_exists('theme_wp_title')){
	/**
	 * Filtre le titre de la page.
	 * 
	 * Crée un titre formatté et plus précis pour l'affichage
	 * dans le head du document, basée sur la page actuelle.
	 * 
	 * @hooked wp_title
	*/
	function theme_wp_title( $title, $sep ) {
		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name', 'display' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'custom_theme' ), max( $paged, $page ) );

		return $title;
	}
}
add_filter( 'wp_title', 'theme_wp_title', 10, 2 );


if(!function_exists('theme_widgets_init')){
	/**
	 * Enregistre les widgets de base.
	 * 
	 * @hooked widgets_init
	 */
	function theme_widgets_init() {
		register_sidebar( array(
			'name'          => __( 'Main Widget Area', 'custom_theme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Appears in the footer section of the site.', 'custom_theme' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Secondary Widget Area', 'custom_theme' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Appears on posts and pages in the sidebar.', 'custom_theme' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'theme_widgets_init' );


if ( ! function_exists( 'theme_paging_nav' ) ){
	/**
	 * Affiche la navigation au suivant/précédent groupe d'article lorsque nécessaire.
	 */
	function theme_paging_nav() {
		global $wp_query;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 )
			return;
		?>
		<nav class="navigation paging-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'custom_theme' ); ?></h1>
			<div class="nav-links">

				<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-previous"><? previous_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'custom_theme' ) ); ?></div>
				<?php endif; ?>

				<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-next"><? next_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'custom_theme' ) ); ?></div>
				<?php endif; ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}


if ( ! function_exists( 'theme_post_nav' ) ){
	/**
	 * Affiche la navigation au suivant/précédent article lorsque nécessaire.
	 */
	function theme_post_nav() {
		global $post;

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'custom_theme' ); ?></h1>
			<div class="nav-links">
				<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'custom_theme' ) ); ?>
				<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'custom_theme' ) ); ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}


if ( ! function_exists( 'theme_entry_meta' ) ){
	/**
	 * Affiche du HTML avec les informations meta pour l'article actuel: categories, tags, permalien, auteur, et date.
	 */
	function theme_entry_meta() {
		if ( is_sticky() && is_home() && ! is_paged() )
			echo '<span class="featured-post">' . __( 'Sticky', 'custom_theme' ) . '</span>';

		if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
			twentythirteen_entry_date();

		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list( __( ', ', 'custom_theme' ) );
		if ( $categories_list ) {
			echo '<span class="categories-links">' . $categories_list . '</span>';
		}

		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', __( ', ', 'custom_theme' ) );
		if ( $tag_list ) {
			echo '<span class="tags-links">' . $tag_list . '</span>';
		}

		// Post author
		if ( 'post' == get_post_type() ) {
			printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'custom_theme' ), get_the_author() ) ),
				get_the_author()
			);
		}
	}
}




/**
 * Ajoute des classes au classes par défaut du body de WordPress.
 * 
 * @hooked body_class
*/
function theme_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';

	return $classes;
}
add_filter( 'body_class', 'theme_body_class' );



/**
 * Enqueue les handlers postMessage Javascript pour le Customizer.
 * 
 * Associes les handlers Javascript pour rendre le preview du Customizer
 * recharger automatiquement lors de changement.
 * 
 * @hooked customize_preview_init
 */
function customize_preview_js() {
	wp_enqueue_script( 'theme-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20130226', true );
}
add_action( 'customize_preview_init', 'customize_preview_js' );

?>