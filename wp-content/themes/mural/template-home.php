<?php
/**
 * Template Name: Accueil
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<div class="content-wrap">
			<? the_content(); ?>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>