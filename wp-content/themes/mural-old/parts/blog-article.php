<div class="article toAnimate animS_fadeIn">
    <div class="content">
        <figure>
            <a href="<?php the_permalink(); ?>">
                <?= wp_get_attachment_image(get_field('image_a_la_une'), 'blog-preview'); ?>
            </a>
        </figure>
        <div class="description">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php if(has_category()): ?>
                <!--<div class="blog-categories">
                    <?= get_the_category_list( ', ', '', get_the_ID() ); ?>
                </div>-->
            <?php endif;  ?>
            <?= truncate(get_field('resume'), 145, "&hellip;", false); ?>
            <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('read more', 'site-theme'); ?><span> ></span></a>
        </div>
    </div>
</div>