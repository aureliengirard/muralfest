<?php $lien = get_sub_field('lien'); ?>
<figure>
    <?php if($lien): ?>
        <a href="<?= $lien['url'] ?>" target="<?= $lien['target'] ?>">
    <?php endif; ?>

        <?= wp_get_attachment_image(get_sub_field('image'), 'full'); ?>

    <?php if($lien): ?>
        </a>
    <?php endif; ?>
</figure>