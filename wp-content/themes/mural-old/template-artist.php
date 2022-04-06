<?php
/**
 * Template Name: Artistes
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" class="site-content">
        <?php get_template_part('parts/inc', 'banner'); ?>

		<div class="content-wrap">
            <?php if(get_the_content()): ?>
                <section class="basic-content">
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php get_template_part('parts/inc', 'order-artist'); ?>

            <section class="list-artists">
                <div class="content">
                <?php
                    global $place_holder_artist;
                    $place_holder_artist = get_field ("artwork_placeholder");

                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                        'post_type' => array( 'artist' ),
                        'posts_per_page' => 18,
                        'nopaging' => false,
                        'paged' => $paged,
                        'orderby' => array(
                            'years' => 'DESC',
                            'title' => 'ASC'
                        ),
                        'meta_query' => array(
                            'years' => array(
                                'key' => 'annee'
                            )
                        )
                    );

                    if (isset($_GET['years']) && $_GET['years'] != '') {
                        $args['meta_query']['years']['value'] = sanitize_text_field($_GET['years']);                    
                    }

                    $query = new WP_Query( $args );
                    global $wp_query;
                    // Put default query object in a temp variable
                    $tmp_query = $wp_query;
                    // Now wipe it out completely
                    $wp_query = null;
                    // Re-populate the global with our custom query
                    $wp_query = $query;

                    $current_year = NULL;
            
                    if ( $query->have_posts() ) : ?>
                        <section class="artists">
                            <?php while ( $query->have_posts() ) :
                                $query->the_post();

                                $artist_year = get_field('annee');

                                if($current_year === NULL){
                                    $current_year = $artist_year;

                                    echo '<h2 class="artists-year">'.$current_year.'</h2>';
                                    echo '<div class="artists-wrap">';
                                }

                                if($current_year != $artist_year){
                                    $current_year = $artist_year;

                                    echo '</div>';
                                    echo '<h2 class="artists-year">'.$current_year.'</h2>';
                                    echo '<div class="artists-wrap">';
                                }
                                ?>
                
                                <?php get_template_part('parts/artist', 'article'); ?>

                            <?php endwhile; ?>
                            <?php echo '</div>'; // Ferme le dernier div de artist-year ?>
                        </section>
                        <?php get_template_part('parts/program', 'pager'); ?>

                    <?php else: ?>
                        <p><?php _e('No artist found.', 'site-theme'); ?></p>
                    <?php endif; ?>
            
                    <?php
                    $wp_query = null;
                    $wp_query = $tmp_query;
                    wp_reset_postdata(); ?>
                </div>
			</section>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>