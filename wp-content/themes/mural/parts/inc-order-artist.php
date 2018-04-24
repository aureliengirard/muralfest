<?php

$style_value = '';
if (isset($_GET['style'])) {
    $style_value = sanitize_text_field ($_GET['style']);
}


?>
<section class="filters">
    <div class="content">
        <form class="program-filters artist-filters" action="<?php the_permalink(); ?>">

                 <?php
                
                $taxonomy = 'style';
                $terms = get_terms($taxonomy); // Get all terms of a taxonomy
    
                if ($terms) : ?>
                    
                <select name="style" id="filter-style" placeholder="<?php _e('Styles', 'site-theme'); ?>">
                   
                     <option value=""></option>

                    <?php foreach ($terms as $term):
                    
                        $selected = false;
                        if($style_value == $term->slug){
                            $selected = true;
                        }
                        ?>
                        <option value="<?= $term->slug; ?>"<?= ($selected ? 'selected="selected"' : '') ?>><?= $term->name; ?></option>

                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>

            <button type="submit" class="button"><?php _e('Filter', 'site-theme'); ?></button>
        </form>
    </div>
</section>