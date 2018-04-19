<div class="program">
    <figure>
        <a href="<?php the_permalink(); ?>">
            <span class="regular-img">
                <?= wp_get_attachment_image(get_field('image_de_levenement'), 'cta-preview'); ?>
            </span>

            <span class="hover-effect-img"><?= wp_get_attachment_image(get_field('image_de_levenement'), 'cta-preview'); ?></span>
        </a>
    </figure>

    <div class="description">
        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
        <p class="event-infos">
            <span class="date">
                <?= date_i18n('j F Y', strtotime(get_field('event_date'))); ?> | <?php the_field('heure_de_debut'); ?>
                <?php if(get_field('heure_de_fin')){
                    echo ' '.__('to', 'site-theme').' '.get_field('heure_de_fin');
                } ?>
            </span>
            <span class="venue"><?= get_the_title(get_field('lieu')); ?></span>
        </p>
        <?= truncate(get_field('resume'), 50, "", true); ?>
        <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('Learn more +', 'site-theme'); ?></a>
    </div>
</div>