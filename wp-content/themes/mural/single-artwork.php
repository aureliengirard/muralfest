<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		
		<div class="content-wrap">
            <section class="basic-content">
                <div id="gmap"></div>
                <div class="content">
                    <section class="back-btn">
                        <a class="readmore" href="<?= wp_get_referer() ?>"><?php _e('Back', 'custom_theme') ?></a>
                    </section>

                    <section class="col-wrapper">
                        <div class="left-col">
                            <figure>
                                <?= wp_get_attachment_image(get_field('image_de_loeuvre'), 'original'); ?>
                            </figure>
                        </div>
                        <div class="right-col">
                            <h1><?php the_title(); ?></h1>
                            <h3 class="artist">
                                <span><?php _e('By:', 'site-theme'); ?></span> <?= get_the_title(get_field('artiste')); ?></h3>
                            <p class="date"><?php _e('Year of completion:', 'site-theme'); ?> <?php the_field('annee'); ?></p>
                            <?php get_template_part('parts/inc', 'share'); ?>
                        </div>
                    </section>
                </div>
            </section>

            <?php get_template_part('parts/inc', 'background_content'); ?>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>