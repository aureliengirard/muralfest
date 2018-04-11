<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		
		
		<div class="content-wrap">
            <section class="basic-content">
                <div class="content">
                    <?= displayBackBtn(); ?>

                    <figure>
                        <?= wp_get_attachment_image(get_field('image_de_levenement'), 'medium'); ?>
                    </figure>
                    <h1><?php the_title(); ?></h1>
                    <?php get_template_part('parts/inc', 'share'); ?>
                    <p class="date"><?php the_field('date_et_heure'); ?></p>
                    <?php if(get_field('evenement_facebook')): ?>
                        <a href="<?php the_field('evenement_facebook'); ?>" target="_blank" rel="nofollow"><?php _e('View Facebook event', 'site-theme'); ?></a>
                    <?php endif; ?>
                </div>
            </section>

            <?php get_template_part('parts/inc', 'content'); ?>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>