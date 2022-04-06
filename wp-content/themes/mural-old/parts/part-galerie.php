<div class="content">
    <?php if ( have_rows( 'images' ) ) : ?>
        <div class="gallery">
            <?php while ( have_rows( 'images' ) ) : the_row(); ?>
                <figure>
                    <a href="<?= wp_get_attachment_image_url(get_sub_field('image'), 'original'); ?>">
                        <?= wp_get_attachment_image(get_sub_field('image'), 'medium'); ?>
                    </a>
                </figure>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>