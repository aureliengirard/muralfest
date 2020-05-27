<?php

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
	<style type="text/css">
		body {
			padding-bottom: 72px;
			margin-bottom: 7px;
			background-color: #2c2034 !important;

		}
	</style>
</head>

<? $body_class = array('preload'); ?>

<body <?php body_class($body_class); ?>>
	<div>
		<!--pour mmenu-->
		<div id="page" class="hfeed site">

			<main id="main" class="site-main site-content" role="main">