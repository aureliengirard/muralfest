<?php


if($_SERVER['HTTP_HOST'] != "localhost"){
    if(session_status() == PHP_SESSION_NONE){ // Pour éviter une erreur si une session existe déjà
		session_start();
	}
	
    if(isset($_GET['validation']) && $_GET['validation']==1){
        $_SESSION['validation'] = true;
    }

    if(!isset($_SESSION['validation']) || $_SESSION['validation'] !== true){
        exit();
    }
}

/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */

?>
<!DOCTYPE html>
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
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1, maximum-scale=1">

	<title><?php wp_title('|', true, 'right'); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->

	<?php wp_head(); ?>

</head>

<? $body_class = array('preload'); ?>

<body <?php body_class($body_class); ?>>
	<div>
		<!--pour mmenu-->
		<div id="page" class="hfeed site">

			<header id="masthead" role="banner">
				<div class="main-menu">
					<div class="content">

						<a class="home-link" href="<?php echo esc_url(home_url('/')); ?>/v2019" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
							<?php echo wp_get_attachment_image(get_field('logo', 'options'), 'original'); ?>
						</a>

						<a href="#mmenu" id="open-mmenu"><i class="fas fa-bars"></i></a>

						<nav class="navigation main-navigation" role="navigation">
							<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav-menu', 'walker' => new MenuWalker())); ?>
						</nav>

						<nav id="mmenu">
							<ul>

								<? wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => '', 'container' => '', 'items_wrap' => '%3$s', 'walker' => new MenuWalker()) ); ?>

							</ul>
						</nav>
					</div>
				</div>

			</header><!-- #masthead -->

			<main id="main" class="site-main site-content" role="main">