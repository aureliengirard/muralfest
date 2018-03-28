<?php
/**
 * Template Name: Festival
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<div class="content-wrap">
            <div class="content">
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </div>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>