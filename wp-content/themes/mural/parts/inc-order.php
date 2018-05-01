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
                <input type="text" name="date" value="<?= $date_value ?>" placeholder="<?php _e('Filter by date', 'site-theme'); ?>" />
                <div></div>
            </div>

            <?php
            $args = array(
                'post_type' => array( 'artist' ),
                'posts_per_page' => '-1',
                'order' => 'ASC',
                'order_by' => 'title',       

            );
            $query = new WP_Query( $args );

                if ( $query->have_posts() ) : ?>
                    
                <select name="filtre-artiste" placeholder="<?php _e('Artists', 'site-theme'); ?>">
                   
                     <option value=""></option>

                    <?php while ( $query->have_posts() ) :
                        $query->the_post();

                        /* Get only artist that have events */
                    $program_args = array(
                        'post_type' => array('program'),
                        'posts_per_page' => -1,
                        'nopaging' => true,                       
                    );



                         $program_args['meta_query'][] = array(
                            'key' => 'artiste',
                            'value' => serialize(strval(get_the_ID())),
                            'compare' => 'LIKE'
                        );
                        $program_query = new WP_Query($program_args);
                   

                        if ($program_query->have_posts()) :
                        
                        $selected = false;
                        $artist_slug = $post->post_name;
                        if($artist_value == $artist_slug){
                            $selected = true;
                        }
                        
                        ?>
                        <option value="<?= $artist_slug; ?>"<?= ($selected ? 'selected="selected"' : '') ?>><?php the_title(); ?></option>
                    <?php endif; ?>     
                    <?php endwhile; ?>
                </select>
            <?php endif; ?>

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