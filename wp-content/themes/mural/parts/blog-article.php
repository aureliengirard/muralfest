<div class="article">
    <figure>
        <a href="<?php the_permalink(); ?>">
            <?= wp_get_attachment_image(get_field('image_a_la_une'), 'full'); ?>
        </a>
    </figure>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <p class="date"><?= get_the_date('j F Y'); ?></p>
    <?= get_the_category_list( ', ', '', get_the_ID() ); ?>
    <?= truncate(get_field('resume'), 150, "&hellip;", true); ?>
    <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('Read more', 'site-theme'); ?></a>
</div>