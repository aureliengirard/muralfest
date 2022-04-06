<div class="content">
    <?php if(get_sub_field('titre')): ?>
        <h3><?php the_sub_field('titre') ?></h3>
    <?php endif; ?>

    <?php the_sub_field('texte') ?>

    <button class="button"><?php _e('Email','site-theme')?></button>

    
</div>