<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		
		<div class="content-wrap">
            <section class="basic-content">
                <div id="gmap"></div>
                <div class="content">
                    <section class="single-navigation">
                        <a class="readmore back-btn" href="<?= wp_get_referer() ?>">< <?php _e('Back', 'custom_theme') ?></a>

                        <?php displayBreadcrumb(); ?>
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
                                <span><?php _e('By:', 'site-theme'); ?></span> <a href="<?= get_the_permalink(get_field('artiste')) ?>"><?= get_the_title(get_field('artiste')); ?></a></h3>
                            <p class="date"><?php _e('Year of completion:', 'site-theme'); ?> <?php the_field('annee'); ?></p>
                            <?php if(get_field('ajoute_description')){
                                the_field('description_de_loeuvre'); 
                            }else{
                                $artisteID = get_field('artiste');
                                the_field('biographie',$artisteID);
                            } 
                            ?>
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