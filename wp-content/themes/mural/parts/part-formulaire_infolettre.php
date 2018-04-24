<div class="content">
    <?php if(get_sub_field('titre')): ?>
        <h3><?php the_sub_field('titre') ?></h3>
    <?php endif; ?>

    <?php the_sub_field('texte') ?>

    <form class="newsletter">
        <div class="input-wrap">
            <input type="text" name="email" placeholder="<?php _e('Email', 'site-theme'); ?>" />
            <button type="submit" class="button">
                <i class="fas fa-angle-right"></i>
                <i class="fas fa-angle-right"></i>
            </button>
        </div>
        <p class="error-log"></p>
    </form>
</div>