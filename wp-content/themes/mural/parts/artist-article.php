<div class="program">
    <?
    global $place_holder_artist;
    $artist_id = icl_object_id(get_the_ID(), 'artist', false, "fr");
    
    $image_id = $place_holder_artist["ID"];

    if(get_field('image_de_lartiste', $artist_id)){
        $image_id = get_field('image_de_lartiste', $artist_id);

    }else{
        $artist_artworks_img = get_artist_artworks_images($artist_id);

        if(!empty($artist_artworks_img)){
            $image_id = $artist_artworks_img[0]['image_id']; // 0 pour la dernière oeuvre
        }
    } ?>
            
<figure>
        <a href="<?php the_permalink(); ?>">
            <span class="regular-img">
                <?= wp_get_attachment_image($image_id, 'cta-preview'); ?>
            </span>
            <span class="hover-effect-img"><?= wp_get_attachment_image($image_id, 'cta-preview'); ?></span>
        </a>
    </figure>
  
    <div class="description">
        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>       
        <?= truncate(get_field('biographie'), 60, "&hellip;", true); ?>
        <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('Learn more +', 'site-theme'); ?></a>
    </div>
</div>