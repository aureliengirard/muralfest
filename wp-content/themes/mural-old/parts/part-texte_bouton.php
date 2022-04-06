<div class="text-button-wrapper">
    <div class="content">
    <?php if(get_sub_field('texte')): ?>
        <p class="texte-gauche"><?php the_sub_field('texte'); ?></p>
    <?php endif; ?>

    <?php $link = get_sub_field('bouton');?>
    <a class="button" href="<?php echo $link['url']; ?>"><?php echo $link['title'] ?></a>
    </div>
</div>
