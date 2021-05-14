<?php
$date_value = '';
if(isset($_GET['date'])){
    $date_value = $_GET['date'];
}

$artist_value = '';
if(isset($_GET['filtre-artiste'])){
    $artist_value = sanitize_text_field($_GET['filtre-artiste']);
    
}

$caterogy_value = '';
if (isset($_GET['category'])) {
    $caterogy_value = sanitize_text_field ($_GET['category']);
}


?>
<section class="filters">
    <div class="content">
        <form class="program-filters" action="<?php the_permalink(); ?>">
            <div id="orderby-wrap">
                <input type="text" name="date" value="<?= $date_value ?>" placeholder="<?php _e('Filter by date', 'site-theme'); ?>" autocomplete="off" readonly="true" />
                <div></div>
            </div>

             <?php
                $taxonomy = 'event-category';
                $terms = get_terms($taxonomy); // Get all terms of a taxonomy

                if ($terms) : ?>
                    
                <select name="category" placeholder="<?php _e('Categories', 'site-theme'); ?>">
                   
                     <option value=""></option>

                    <?php foreach ($terms as $term):
                    
                        $selected = false;
                        if($caterogy_value == $term->slug){
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