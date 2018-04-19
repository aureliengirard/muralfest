<?php
/**
 * Template Name: Carte dynamique
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" class="site-content">
        <?php get_template_part('parts/inc', 'banner'); ?>
        
		<div class="content-wrap">
            <div class="content">
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </div>
            
            <div id="gmap-arts"></div>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>