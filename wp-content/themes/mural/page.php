<?php
/**
 * The template for displaying all pages
 *
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<div class="content-wrap">
			<?php get_template_part('parts/inc', 'background_content'); ?>
		</div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>