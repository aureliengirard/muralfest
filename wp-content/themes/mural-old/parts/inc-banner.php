<div class="banner">

    <?php if(get_field('nombre_dimage')): // true or false, true = deux col ?>

        <?php
        $left_banner = get_field('banniere_gauche');
        $right_banner = get_field('banniere_droite');
        ?>
        <?php if($left_banner['banniere']): ?>
            <figure class="left-banner">
                <?php echo wp_get_attachment_image( $left_banner['banniere'], 'max-banner' ); ?>
                <?php if($left_banner['contenu_banniere']): ?>
                    <div class="banner-overlay">
                        <?= $left_banner['contenu_banniere']; ?>
                    </div>
                <?php endif; ?>
            </figure>
        <?php else : ?>
            <div class="gradient-wrapper left-banner">
                <div class="banner-overlay">
                    <?= $left_banner['contenu_banniere']; ?>
                </div>
            </div>
        <?php endif ?>

        <figure class="right-banner">
            <?php echo wp_get_attachment_image( $right_banner['banniere'], 'max-banner' ); ?>
            <?php if($right_banner['contenu_banniere']): ?>
                <div class="banner-overlay">
                    <?= $right_banner['contenu_banniere']; ?>
                </div>
            <?php endif; ?>
        </figure>

    <?php elseif(get_field( 'banniere' )): ?>
        <div class="single-banner-wrap">
            <figure class="single-banner with-overlay" style="background-image:url('<?= wp_get_attachment_image_url(get_field('banniere'), 'max-banner') ;?>')">
                <?php
                if (!get_field('contenu_banniere')) :
                    echo wp_get_attachment_image( get_field( 'banniere' ), 'max-banner' );
                endif;
                ?>
                <?php if(get_field('contenu_banniere')): ?>
                    <div class="banner-overlay  animated fadeInLeft">
                        <?php the_field('contenu_banniere'); ?>
                    </div>
                <?php endif; ?>
            </figure>
        </div>

    <?php endif; ?>
</div>