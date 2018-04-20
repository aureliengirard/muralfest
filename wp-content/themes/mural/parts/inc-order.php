<?php
$date_value = '';
if(isset($_GET['daterange'])){
    $date_value = $_GET['daterange'];
}

$artist_value = 0;
if(isset($_GET['selected-artist'])){
    $artist_value = (Int) $_GET['selected-artist'];
}
?>
<section class="filters">
    <div class="content">
        <form class="program-filters" action="<?php the_permalink(); ?>">
            <div id="orderby-wrap">
                <input type="text" name="daterange" value="<?= $date_value ?>" placeholder="<?php _e('Filter by date', 'site-theme'); ?>" />
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
                    
                <select name="selected-artist" placeholder="<?php _e('Filter by artist', 'site-theme'); ?>">
                   
                     <option value=""></option>

                    <?php while ( $query->have_posts() ) :
                        $query->the_post();
                        
                        $selected = false;
                        if($artist_value == get_the_ID()){
                            $selected = true;
                        }
                        ?>
                        <option value="<?php the_ID(); ?>"<?= ($selected ? 'selected="selected"' : '') ?>><?php the_title(); ?></option>

                    <?php endwhile; ?>
                </select>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

            <button type="submit" class="button"><?php _e('Filter', 'site-theme'); ?></button>
        </form>
    </div>
</section>