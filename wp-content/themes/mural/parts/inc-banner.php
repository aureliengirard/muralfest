<div class="banner">
    <?php if(get_field('nombre_dimage')): // true or false, true = deux col ?>
        <?php   
        $left_banner = get_field('banniere_gauche');
        $right_banner = get_field('banniere_droite');
        ?>
        <figure class="left-banner">
            <?php echo wp_get_attachment_image( $left_banner['banniere'], 'max-banner' ); ?>
            <?php if($left_banner['contenu_banniere']): ?>
                <div class="banner-overlay">
                    <?= $left_banner['contenu_banniere']; ?>
                </div>
            <?php endif; ?>
        </figure>
        <figure class="right-banner">
            <?php echo wp_get_attachment_image( $right_banner['banniere'], 'max-banner' ); ?>
            <?php if($right_banner['contenu_banniere']): ?>
                <div class="banner-overlay">
                    <?= $right_banner['contenu_banniere']; ?>
                </div>
            <?php endif; ?>
        </figure>
    <?php   elseif(get_field( 'banniere' )): ?>
    <figure class="single-banner  <?php if (get_field('contenu_banniere')) : ?> with-overlay <? endif; ?>" <?php if (get_field('contenu_banniere')) : ?> style="background-image:url('<?= wp_get_attachment_image_url(get_field('banniere'), 'max-banner') ;?>')"; <? endif; ?>>
            <?php
            if (!get_field('contenu_banniere')) :
                echo wp_get_attachment_image( get_field( 'banniere' ), 'max-banner' ); 
            endif;
            ?>
            <?php if(get_field('contenu_banniere')): ?>
                <div class="banner-overlay">
                    <?php the_field('contenu_banniere'); ?>
                </div>
            <?php endif; ?>
        </figure>
    <?php endif; ?>
</div>