<?php
/**
 * Template Name: Festival
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<div class="content-wrap">
            <?php get_template_part('parts/inc', 'content'); ?>
        </div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>