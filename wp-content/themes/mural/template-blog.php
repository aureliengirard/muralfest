<?php
/**
 * Template Name: Blogue
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content page-blog">
		<div class="content-wrap">
            <section class="basic-content">
                <div class="content">
                    <? the_content(); ?>

                    <div class="col-wrapper">
                        <div class="left-col">
                            <?php
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                            $args = array(
                                'post_type' => array( 'post' ),
                                'posts_per_page' => get_option( 'posts_per_page' ),
                                'nopaging' => false,
                                'paged' => $paged,
                            );

                            $query = new WP_Query( $args );
                            global $wp_query;
                            // Put default query object in a temp variable
                            $tmp_query = $wp_query;
                            // Now wipe it out completely
                            $wp_query = null;
                            // Re-populate the global with our custom query
                            $wp_query = $query;
                            
                            if ( $query->have_posts() ) : ?>
                                <div class="articles">
                                    <?php while ( $query->have_posts() ) :
                                        $query->the_post(); ?>
                        
                                        <?php get_template_part('parts/blog', 'article'); ?>

                                    <?php endwhile; ?>
                                </div>
                                <?php get_template_part('parts/blog', 'pager'); ?>

                            <?php else: ?>
                                <p><?php _e('No post found.', 'site-theme'); ?></p>
                            <?php endif; ?>
                    
                            <?php 
                            $wp_query = null;
                            $wp_query = $tmp_query;
                            wp_reset_postdata(); ?>
                        </div>
                        <div class="right-col">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                </div>
            </section>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>