<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content single-artwork">
		
		
		<div class="content-wrap">
            <section class="basic-content">
                <div class="content">
                    <?= displayBackBtn(); ?>

                    <figure>
                        <?= wp_get_attachment_image(get_field('image_de_loeuvre'), 'medium'); ?>
                    </figure>
                    <div class="col-wrapper">
                        <div class="left-col">
                            <h1><?php the_title(); ?></h1>
                            <p class="author"><a href="get_the_permalink(get_field('artiste'))"><?= get_the_title(get_field('artiste')); ?></a></p>
                            <p class="date"><?php _e('Year of completion:', 'site-theme'); ?> <?php the_field('annee'); ?></p>
                            <?php get_template_part('parts/inc', 'share'); ?>
                        </div>
                        <div class="right-col">
                            <div id="gmap"></div>
                        </div>
                    </div>

                </div>
            </section>

            <?php get_template_part('parts/inc', 'background_content'); ?>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>