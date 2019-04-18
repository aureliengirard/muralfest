<?php 
    global $place_holder_artist;
    $artist_id = icl_object_id(get_the_ID(), 'artist', false, "fr");

    $image_id = $place_holder_artist["ID"];

    if(get_field('image_de_lartiste', $artist_id)){
        $image_id = get_field('image_de_lartiste', $artist_id);
    }else{     
        $artist_artworks_img = get_artist_artworks_images($artist_id);
        if(!empty($artist_artworks_img)){
            $artist_artwork = true;
            $image_id = $artist_artworks_img[0]['image_id']; // 0 pour la dernière oeuvre
        }
    }
?>

<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		<div class="content-wrap">
            <section class="basic-content">
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
                        <?php elseif($artist_artwork): ?> 
                            <div class="left-col">
                                <figure>
                                    <?= wp_get_attachment_image($image_id, 'original'); ?>
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
                                <h3 class="styles"><?= $text_styles ?></h3>
                            <?php endif; ?>

                            <?php
                            $acf_country = new acf_country_helpers();
                            $country_list = $acf_country->get_countries(ICL_LANGUAGE_CODE.'_CA');
                            $country = $country_list[get_field('pays_dorigine')];
                            ?>

                            <p class="country"><span><?php _e('Native country:', 'site-theme'); ?></span> <?php echo $country; ?></p>

                            <?php if(get_field('siteweb')): ?>
                                <a class="button smaller artist-socials" href="<?php the_field('siteweb'); ?>" target="_blank" rel="nofollow"><?php _e('Website', 'site-theme'); ?></a>
                            <?php endif; ?>
                            
                            <?php if(get_field('instagram')): ?>
                                <a class="button smaller instagram artist-socials" href="<?php the_field('instagram'); ?>" target="_blank" rel="nofollow"><?php _e('Instagram', 'site-theme'); ?></a>
                            <?php endif; ?>

                            <?php if(get_field('facebook')): ?>
                                <a class="button smaller artist-socials" href="<?php the_field('facebook'); ?>" target="_blank" rel="nofollow"><?php _e('Facebook', 'site-theme'); ?></a>
                            <?php endif; ?>

                            <?php if(get_field('twitter')): ?>
                                <a class="button smaller twitter artist-socials" href="<?php the_field('twitter'); ?>" target="_blank" rel="nofollow"><?php _e('Twitter', 'site-theme'); ?></a>
                            <?php endif; ?>
                        </div>
                        
                    </section>

                    <section class="bio">
                        <?php the_field('biographie'); ?>
                    </section>
                </div>
                <div class="post-nav-wrapper">
                    <div class="arrow-container prev-link"><?php previous_post_link('%link','<i class="far fa-angle-left"></i> <span>%title</span> ') ?></div>
                    <div class="arrow-container next-link"><?php next_post_link('%link','<span>%title</span> <i class="far fa-angle-right"></i>') ?></div>
                </div>
               
                
               
            </section>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>