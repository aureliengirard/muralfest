<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */

?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1, maximum-scale=1">
	
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	
	<?php wp_head(); ?>
	
</head>

<? $body_class = array('preload'); ?>

<body <?php body_class($body_class); ?>>
<div><!--pour mmenu-->
	
	<div id="page" class="hfeed site">
		
		<header id="masthead" role="banner">
			<div id="topbar">
				<ul class="sociaux">
					<?php get_template_part('parts/inc', 'sociaux'); ?>
				</ul>
			</div>
			
			<div class="main-menu">
				<a href="#mmenu" id="open-mmenu"><i class="fas fa-bars"></i></a>
				
				<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php echo wp_get_attachment_image( get_field( 'logo', 'options' ), 'original' ); ?>
				</a>
				
				<nav class="navigation main-navigation" role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'walker' => new MenuWalker() ) ); ?>
				</nav>
				
				<nav id="mmenu">
					<ul>
						<li id="search-mob"><? get_search_form(); ?></li>
						<? wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => '', 'container' => '', 'items_wrap' => '%3$s', 'walker' => new MenuWalker()) ); ?>
						
						<li><ul><?php get_template_part('parts/inc', 'sociaux'); ?></ul></li>
						<? HLP()->language_selector(); ?>
					</ul>
				</nav>
			</div>
			
		</header><!-- #masthead -->

		<main id="main" class="site-main site-content" role="main">