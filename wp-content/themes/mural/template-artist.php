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

            <section class="list-programs">
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
                        'orderby' =>'post_title',
                        'order' =>'ASC'
                    );

                    

                    if (isset($_GET['style']) && $_GET['style'] != '') {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'style',
                            'field' => 'slug',
                            'terms' => sanitize_text_field($_GET['style']),
                        );                    
                    }

                    $query = new WP_Query( $args );
                    global $wp_query;
                    // Put default query object in a temp variable
                    $tmp_query = $wp_query;
                    // Now wipe it out completely
                    $wp_query = null;
                    // Re-populate the global with our custom query
                    $wp_query = $query;
            
                    if ( $query->have_posts() ) : ?>
                        <section class="programs">
                            <?php while ( $query->have_posts() ) :
                                $query->the_post();
                                ?>
                
                                <?php get_template_part('parts/artist', 'article'); ?>

                            <?php endwhile; ?>
                        </section>
                        <?php get_template_part('parts/program', 'pager'); ?>

                    <?php else: ?>
                        <p><?php _e('No program found.', 'site-theme'); ?></p>
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