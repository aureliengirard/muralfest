<div class="program">
    <?
    global $place_holder_artist;
    $artist_id = icl_object_id(get_the_ID(), 'artist', false, "fr");      
    $artwork_args = array(
        'post_type' => array('artwork'),
        'posts_per_page' => -1,
        'meta_key' => 'artiste',
        'meta_value' => $artist_id,
        'orderby' => 'annee',
        'order' => 'DESC'
    );

            $artwork_query =  new WP_Query($artwork_args);
    
            if ($artwork_query->have_posts()) :
        
                global $wp_query;
                // Put default query object in a temp variable
                $tmp_query = $wp_query;
                // Now wipe it out completely
                $wp_query = null;
                // Re-populate the global with our custom query
                $wp_query = $artwork_query;
               
                while ($artwork_query->have_posts()) :
                    $artwork_query->the_post();           
                    if(get_field('image_de_loeuvre')){
                         $art =  wp_get_attachment_image(get_field('image_de_loeuvre'), 'cta-preview');
                    }                            
                endwhile;
                $wp_query = null;
                $wp_query = $tmp_query;
                wp_reset_postdata();
            endif;
             if (!isset($art) || $art===false) {
                $art = wp_get_attachment_image($place_holder_artist["ID"], 'cta-preview');        
            }
            ?>
            
<figure>
        <a href="<?php the_permalink(); ?>">
            <span class="regular-img">
                <?= $art; ?>
            </span>
            <span class="hover-effect-img"><?= $art; ?></span>
        </a>
    </figure>
  
    <div class="description">
        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        <p class="event-infos">
            
            <span class="venue"><?= get_the_title(get_field('lieu')); ?></span>
        </p>
        <?= truncate(get_field('biographie'), 60, "&hellip;", true); ?>
        <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('Learn more +', 'site-theme'); ?></a>
    </div>
</div>