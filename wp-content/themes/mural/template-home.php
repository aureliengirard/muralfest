<?php
/**
 * Template Name: Accueil
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<?php get_template_part('parts/inc', 'banner'); ?>

		<div class="content-wrap">
			<?php get_template_part('parts/inc', 'background_content'); ?>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>