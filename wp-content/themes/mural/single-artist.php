<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		
		<div class="content-wrap">
            <section class="basic-content">
                <div id="gmap"></div>
                <div class="content">
                    <section class="single-navigation">
                        <?php display_back_button(); ?>

                        <?php displayBreadcrumb(); ?>
                    </section>

                    <section class="col-wrapper">
                        <?php if(wp_get_attachment_image(get_field('image_de_lartiste'), 'original')): ?>
                            <div class="left-col">
                                <figure>
                                    <?= wp_get_attachment_image(get_field('image_de_lartiste'), 'original'); ?>
                                </figure>
                                <?php get_template_part('parts/inc', 'share'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="right-col">
                            <h1><?php the_title(); ?></h1>

                            <?php
                            $styles = wp_get_post_terms(get_the_ID(), 'style');
                            $text_styles = '';

                            foreach ($styles as $style) {
                                $text_styles .= $style->name.', ';
                            }

                            $text_styles = trim($text_styles, ', ');
                            ?>

                            <?php if($text_styles): ?>
                                <h3 class="styles"><span><?php _e('Styles:', 'site-theme'); ?></span> <?= $text_styles ?></h3>
                            <?php endif; ?>

                            <?php
                            $acf_country = new acf_country_helpers();
                            $country_list = $acf_country->get_countries(ICL_LANGUAGE_CODE.'_CA');
                            $country = $country_list[get_field('pays_dorigine')];
                            ?>

                            <p class="country"><span><?php _e('Native country:', 'site-theme'); ?></span> <?php echo $country; ?></p>

                            <?php if(get_field('siteweb')): ?>
                                <a class="button smaller" href="<?php the_field('siteweb'); ?>" target="_blank" rel="nofollow"><?php _e('Website', 'site-theme'); ?></a>
                            <?php endif; ?>
                            
                            <?php if(get_field('instagram')): ?>
                                <a class="button smaller instagram" href="<?php the_field('instagram'); ?>" target="_blank" rel="nofollow"><?php _e('Instagram', 'site-theme'); ?></a>
                            <?php endif; ?>

                            <?php if(get_field('facebook')): ?>
                                <a class="button smaller" href="<?php the_field('facebook'); ?>" target="_blank" rel="nofollow"><?php _e('Facebook', 'site-theme'); ?></a>
                            <?php endif; ?>

                            <?php if(get_field('twitter')): ?>
                                <a class="button smaller twitter" href="<?php the_field('twitter'); ?>" target="_blank" rel="nofollow"><?php _e('Twitter', 'site-theme'); ?></a>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="bio">
                        <?php the_field('biographie'); ?>
                    </section>
                </div>
            </section>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>