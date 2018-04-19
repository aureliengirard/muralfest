<?php
/**
 * The template for displaying all singles
*/

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content c12">
		
		<div class="content-wrap">
            <section class="basic-content">
                <div class="content">
                    <?= displayBackBtn(); ?>

                    <figure>
                        <?= wp_get_attachment_image(get_field('image_a_la_une'), 'full'); ?>
                    </figure>
                    <h1><?php the_title(); ?></h1>
                    <p class="date"><?= get_the_date('j F Y'); ?></p>
					<?= get_the_category_list( ', ', '', get_the_ID() ); ?>
                    <?php get_template_part('parts/inc', 'share'); ?>
                </div>
            </section>

            <?php get_template_part('parts/inc', 'background_content'); ?>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>