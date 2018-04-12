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
                    <h3 class="artist">
                        <?php
                        $artists_name = '';
                        foreach(get_field('artiste') as $artist){
                            $artists_name .= get_the_title($artist).', ';
                        }
                        $artists_name = trim($artists_name, ', ');
                        $pos = strrpos($artists_name, ',');

                        if($pos !== false){
                            $artists_name = substr_replace($artists_name, ' '.__('and', 'site-theme'), $pos, strlen(','));
                        }

                        echo $artists_name;
                        ?>
                    </h3>
                    <?php get_template_part('parts/inc', 'share'); ?>
                    <p class="date"><?php the_field('date_et_heure'); ?></p>
                    
                    <?php if(get_field('lien_billets')): ?>
                        <a href="<?php the_field('lien_billets'); ?>" target="_blank" rel="nofollow"><?php _e('Buy your ticket', 'site-theme'); ?></a>
                    <?php endif; ?>

                    <?php if(get_field('lien_evenement_facebook')): ?>
                        <a href="<?php the_field('lien_evenement_facebook'); ?>" target="_blank" rel="nofollow"><?php _e('View Facebook event', 'site-theme'); ?></a>
                    <?php endif; ?>

                    <?php if(get_field('lien_playlist')): ?>
                        <a href="<?php the_field('lien_playlist'); ?>" target="_blank" rel="nofollow"><?php _e('Listen to the playlist', 'site-theme'); ?></a>
                    <?php endif; ?>
                </div>
            </section>

            <?php get_template_part('parts/inc', 'content'); ?>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>