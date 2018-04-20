<div class="article">
    <figure>
        <a href="<?php the_permalink(); ?>">
            <?= wp_get_attachment_image(get_field('image_a_la_une'), 'blog-preview'); ?>
        </a>
    </figure>
    <div class="description">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php if(has_category()): ?>
            <div class="blog-categories">
                <?= get_the_category_list( ', ', '', get_the_ID() ); ?>
            </div>
        <?php endif;  ?>
        <?= truncate(get_field('resume'), 50, "", true); ?>
        <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('Learn more +', 'site-theme'); ?></a>
    </div>
</div>