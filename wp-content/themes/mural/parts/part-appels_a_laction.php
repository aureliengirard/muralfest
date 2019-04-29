<div class="content">
    <?php if(get_sub_field('titre')): ?>
        <h2><?php the_sub_field('titre'); ?></h2>
    <?php endif; ?>

    <?php if ( have_rows( 'appel_a_laction' ) ) : ?>
        <div class="cta-wrapper">
            <?php while ( have_rows( 'appel_a_laction' ) ) : the_row(); ?>
                <div class="cta toAnimate animS_fadeInUp">
                    <figure>
                        <a href="<?php the_sub_field('lien_page'); ?>">
                            <span class="regular-img">
                                <?= wp_get_attachment_image(get_sub_field('image'), 'cta-preview'); ?>
                            </span>
                        </a>
                    </figure>
                    <div class="description">
                        <h3><a href="<?php the_sub_field('lien_page'); ?>"><?php the_sub_field('titre'); ?></a></h3>
                        
                        <?php if(get_sub_field('description')): ?>
                            <?php the_sub_field('description'); ?>
                            <a class="readmore" href="<?php the_sub_field('lien_page'); ?>"><?php _e('Read more', 'site-theme'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>