<?php
/**
 * The template for displaying all pages
 *
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<header>
			<h1><? the_title(); ?></h1>
		</header>
		
		<div class="content-wrap">
			<?php get_template_part('parts/inc', 'content'); ?>
		</div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>