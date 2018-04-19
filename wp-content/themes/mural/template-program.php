<?php
/**
 * Template Name: Programmation
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

            <?php get_template_part('parts/inc', 'order'); ?>

            <section class="list-programs">
                <div class="content">
                <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                        'post_type' => array( 'program' ),
                        'posts_per_page' => get_option( 'posts_per_page' ),
                        'nopaging' => false,
                        'paged' => $paged,
                        'orderby' => array(
                            'order_event' => 'ASC',
                            'order_start_time' => 'ASC'
                        ),
                        'meta_query' => array(
                            'order_event' => array(
                                'key' => 'event_date'
                            ),
                            'order_start_time' => array(
                                'key' => 'heure_de_debut'
                            )
                        )
                    );

                    if(isset($_GET['selected-artist']) && $_GET['selected-artist'] != ''){
                        $args['meta_query'][] = array(
                            'key' => 'artiste',
                            'value' => serialize(strval($_GET['selected-artist'])),
                            'compare' => 'LIKE'
                        );
                    }

                    if(isset($_GET['daterange']) && $_GET['daterange'] != ''){
                        $posted_date = $_GET['daterange'];

                        if(strrpos($posted_date, ' - ')){
                            $daterange = explode(' - ', $posted_date);

                            $start_date = DateTime::createFromFormat('d/m/Y', $daterange[0]);
                            $end_date = DateTime::createFromFormat('d/m/Y', $daterange[1]);

                            $args['meta_query'][] = array(
                                array(
                                    'key' => 'event_date',
                                    'type' => 'DATE',
                                    'value' => $end_date->format('Ymd'),
                                    'compare' => '<='
                                ),
                                array(
                                    'key' => 'event_date',
                                    'type' => 'DATE',
                                    'value' => $start_date->format('Ymd'),
                                    'compare' => '>='
                                )
                            );

                        }else{
                            $date = DateTime::createFromFormat('d/m/Y', $posted_date);

                            $args['meta_query'][] = array(
                                'key' => 'event_date',
                                'type' => 'DATE',
                                'value' => $date->format('Ymd'),
                                'compare' => '='
                            );
                        }
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
                
                                <?php get_template_part('parts/program', 'article'); ?>

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