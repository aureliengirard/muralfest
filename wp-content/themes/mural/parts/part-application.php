<div class="content">
    <?php if(get_field('image_gauche', 'options')): ?>
        <figure>
            <?= wp_get_attachment_image(get_field('image_gauche', 'options'), 'original'); ?>
        </figure>
    <?php endif; ?>

    <div class="app-info">
        <h3><?php the_field('texte_pour_liens', 'options'); ?></h3>
        <div class="app-links">
            <a href="<?php the_field('app_store', 'options'); ?>" target="_blank" rel="noopener">
                <?php if ( ICL_LANGUAGE_CODE == "en" ){ ?>
                    <img src="<?= CHILDURI ?>/images/app-store.png" alt="App Store" />
                <?php }else{ ?>
                    <img src="<?= CHILDURI ?>/images/app-store-fr.png" alt="App Store" />
                <?php } ?>    
            </a>
            <a href="<?php the_field('google_play', 'options'); ?>" target="_blank" rel="noopener">
              <?php if ( ICL_LANGUAGE_CODE == "en" ){ ?>
                    <img src="<?= CHILDURI ?>/images/google-play.png" alt="Google Play" />
                <?php }else{ ?>
                    <img src="<?= CHILDURI ?>/images/google-play-fr.png" alt="Google Play" />
                <?php } ?>
            </a>
        </div>
    </div>

    <?php if(get_field('image_droite', 'options')): ?>
        <figure>
            <?= wp_get_attachment_image(get_field('image_droite', 'options'), 'original'); ?>
        </figure>
    <?php endif; ?>
</div>