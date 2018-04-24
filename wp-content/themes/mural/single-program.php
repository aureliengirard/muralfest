<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">
		
		<div class="content-wrap">
            <section class="basic-content">
                <div class="content">
                    <section class="single-navigation">
                        <a class="readmore back-btn" href="<?= wp_get_referer() ?>">< <?php _e('Back', 'custom_theme') ?></a>

                        <?php displayBreadcrumb(); ?>
                    </section>

                    <section class="col-wrapper">
                        <div class="left-col">
                            <figure>
                                <?= wp_get_attachment_image(get_field('image_de_levenement'), 'cta-preview'); ?>
                            </figure>
                            <?php get_template_part('parts/inc', 'share'); ?>
                        </div>
                        <div class="right-col">
                            <h1><?php the_title(); ?></h1>

                            <p class="event-infos">
                                <span class="date">
                                <?php
                                if(get_field('date_de_fin')){
                                    echo date_i18n('j F', strtotime(get_field('event_date'))).' '._x('to', 'dates événement', 'site-theme').' '.date_i18n('j F Y', strtotime(get_field('date_de_fin')));

                                }else{
                                    echo date_i18n('j F Y', strtotime(get_field('event_date')));
                                }
                                if(get_field('heure_de_debut')){
                                    echo  ' | '.date_i18n('H:i', strtotime(get_field('heure_de_debut')));

                                    if(get_field('heure_de_fin')){
                                        echo ' '.__('to', 'site-theme').' '. date_i18n('H:i', strtotime(get_field('heure_de_fin')));
                                    }
                                }
                                echo ' | ';
                                ?>
                                </span>
                                <span class="venue"><?= get_the_title(get_field('lieu')); ?></span>
                            </p>

                            <?php if(get_field('artiste')): ?>
                                <h3 class="artist">
                                    <span><?php _e('By:', 'site-theme'); ?></span>
                                    <?php
                                    $artists_name = '';
                                    foreach(get_field('artiste') as $artist){
                                        $artists_name .= '<a href="'.get_the_permalink($artist).'">';
                                        $artists_name .= get_the_title($artist);
                                        $artists_name .= '</a>, ';
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
                            
                            <?php if(get_field('lien_billets')): ?>
                                <a class="button smaller" href="<?php the_field('lien_billets'); ?>" target="_blank" rel="nofollow"><?php _e('Buy your ticket', 'site-theme'); ?></a>
                            <?php endif; ?>

                            <?php if(get_field('lien_evenement_facebook')): ?>
                                <a class="button smaller facebook" href="<?php the_field('lien_evenement_facebook'); ?>" target="_blank" rel="nofollow"><?php _e('View Facebook event', 'site-theme'); ?></a>
                            <?php endif; ?>

                            <?php if(get_field('lien_playlist')): ?>
                                <a class="button smaller playlist" href="<?php the_field('lien_playlist'); ?>" target="_blank" rel="nofollow"><?php _e('Listen to the playlist', 'site-theme'); ?></a>
                            <?php endif; ?>

                            <?php get_template_part('parts/inc', 'background_content'); ?>
                        </div>
                    </section>
                </div>
            </section>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>