<?php

$style_value = '';
if (isset($_GET['style'])) {
    $style_value = sanitize_text_field ($_GET['style']);
}

$year_value = '';
if (isset($_GET['years'])) {
    $year_value = sanitize_text_field ($_GET['years']);
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

                <?php
                    $years = get_field_object('field_5aeb0f481de34');
                    $years = $years['choices'];
                ?>
                <select name="years" id="filter-years" placeholder="<?php _e('Years', 'site-theme'); ?>">
                   
                   <option value=""></option>

                    <?php foreach ($years as $year):
                    
                        $selected = false;
                        if($year_value == $year){
                            $selected = true;
                        }
                        ?>
                        <option value="<?= $year; ?>"<?= ($selected ? 'selected="selected"' : '') ?>><?= $year; ?></option>

                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>

            <button type="submit" class="button"><?php _e('Filter', 'site-theme'); ?></button>
        </form>
    </div>
</section>