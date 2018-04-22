<?php
/**
 * The template for displaying all singles
*/

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">

		<div class="content-wrap">
            <section class="basic-content">
                <div class="content">
                    <section class="back-btn">
                        <a class="readmore" href="<?= wp_get_referer() ?>">< <?php _e('Back', 'custom_theme') ?></a>
                    </section>

                    <section class="col-wrapper">
                        <div class="left-col">
                            <figure>
                                <?= wp_get_attachment_image(get_field('image_a_la_une'), 'original'); ?>
                            </figure>
                            <?php get_template_part('parts/inc', 'share'); ?>
                        </div>

                        <div class="right-col">
                            <h1><?php the_title(); ?></h1>
                            <p class="date"><?= get_the_date('j F Y'); ?></p>
                            <!--<div class="blog-categories">
                                <?= get_the_category_list( ', ', '', get_the_ID() ); ?>
                            </div>-->

                            <?php get_template_part('parts/inc', 'background_content'); ?>
                        </div>
                    </section>
                </div>
            </section>

            
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>