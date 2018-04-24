<div class="content">
    <?php if(get_sub_field('titre')): ?>
        <h2><?php the_sub_field('titre'); ?></h2>
    <?php endif; ?>

    <?php if ( have_rows( 'partenaires' ) ) : ?>
        <div class="partner-wrapper">
            <?php while ( have_rows( 'partenaires' ) ) : the_row();

                if( get_row_layout() == 'partenaire' ):
                
                    $partner_id = get_sub_field('partenaire');
                    $website = get_field('site_web', $partner_id);
                    $name = get_the_title($partner_id);
                    ?>
                    <figure class="partner">
                        <?php if($website): ?>
                            <a href="<?= $website ?>" title="<?php printf("View %s's website", $name); ?>" target="_blank">
                        <?php endif; ?>

                            <?= wp_get_attachment_image( get_field( 'logo', $partner_id ), 'medium', false, array('alt' => $name) ); ?>
                            
                        <?php if($website): ?>
                            </a>
                        <?php endif; ?>
                    </figure>

                <?php elseif( get_row_layout() == 'image' ):
                    
                    $website = get_sub_field('site_web');
                    ?>
                    <figure class="partner-image<?= (get_sub_field('alignement_de_limage') ? ' snap-bottom' : '') ?>">
                        <?php if($website): ?>
                            <a href="<?= $website ?>" target="_blank">
                        <?php endif; ?>

                            <?= wp_get_attachment_image( get_sub_field( 'image' ), 'full' ); ?>
                            
                        <?php if($website): ?>
                            </a>
                        <?php endif; ?>
                    </figure>

                <?php endif; ?>

            <?php endwhile; ?>
        </div>

        <div class="button-wrap">
            <a href="<?= get_the_permalink(Festival()->get_festival_sub_page('partner')); ?>" class="button"><?php _e('View all partners', 'site-theme'); ?></a>
        </div>
    <?php endif; ?>
</div>