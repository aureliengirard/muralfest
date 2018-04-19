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

                    <?php if(get_field('artiste')): ?>
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
                    <?php endif; ?>

                    <?php get_template_part('parts/inc', 'share'); ?>
                    <p class="date">
                    <?php
                    if(get_field('date_de_fin')){
                        echo date_i18n('j F', strtotime(get_field('event_date'))).' '._x('to', 'dates événement', 'site-theme').' '.date_i18n('j F Y', strtotime(get_field('date_de_fin')));
        
                    }else{
                        echo date_i18n('j F Y', strtotime(get_field('event_date')));
                    }
                    echo ' | ';
                    if(get_field('heure_de_debut')){
                        echo  date_i18n('H:i', strtotime(get_field('heure_de_debut')));
        
                        if(get_field('heure_de_fin')){
                            echo ' '.__('to', 'site-theme').' '. date_i18n('H:i', strtotime(get_field('heure_de_fin')));
                        }
                    } ?>
                    </p>
                    
                    <p class="venue"><?= get_the_title(get_field('lieu')); ?></p>
                    
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

            <?php get_template_part('parts/inc', 'background_content'); ?>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>