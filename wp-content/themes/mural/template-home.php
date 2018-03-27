<?php
/**
 * Template Name: Home
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<div>
		<? the_content(); ?>
	</div>
	
<?php endwhile; ?>


<?php get_footer(); ?>