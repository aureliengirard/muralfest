<div class="content">
    <?php if ( have_rows( 'appel_a_laction' ) ) : ?>
        <?php while ( have_rows( 'appel_a_laction' ) ) : the_row(); ?>
            <div class="cta">
                <figure>
                    <a href="<?php the_sub_field('lien_page'); ?>">
                        <?= wp_get_attachment_image(get_sub_field('image'), 'full'); ?>
                    </a>
                </figure>
                <h2><a href="<?php the_sub_field('lien_page'); ?>"><?php the_sub_field('titre'); ?></a></h2>
                <?php the_sub_field('description'); ?>
                <a class="readmore" href="<?php the_sub_field('lien_page'); ?>"><?php _e('Read more', 'site-theme'); ?></a>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>