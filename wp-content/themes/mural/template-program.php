<?php
/**
 * Template Name: Programmation
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<div class="content-wrap">
			<div class="content">
				<? the_content(); ?>

                <?php get_template_part('parts/inc', 'order'); ?>

                <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $args = array(
                        'post_type' => array( 'program' ),
                        'posts_per_page' => get_option( 'posts_per_page' ),
                        'nopaging' => false,
                        'paged' => $paged,
                        'order' => 'ASC',
                        'order_by' => 'meta_value',
                        'meta_key' => 'date_et_heure',
                        'meta_query' => array()
                    );

                    if(isset($_GET['selected-artist']) && $_GET['selected-artist'] != ''){
                        $args['meta_query'][] = array(
                            'key' => 'artiste',
                            'value' => serialize(strval($_GET['selected-artist'])),
                            'compare' => 'LIKE'
                        );
                    }

                    if(isset($_GET['daterange']) && $_GET['daterange'] != ''){
                        $date_meta = array(
                            'key' => 'date_et_heure',
                            'type' => 'DATE'
                        );

                        $posted_date = $_GET['daterange'];

                        if(strrpos($posted_date, ' - ')){
                            $daterange = explode(' - ', $posted_date);

                            foreach($daterange as $key => $date){
                                $date = DateTime::createFromFormat('d/m/Y', $date);
                                $daterange[$key] = $date->format('Y-m-d');
                            }

                            $date_meta = array_merge($date_meta, array(
                                'value' => $daterange,
                                'compare' => 'BETWEEN'
                                )
                            );

                        }else{
                            $date = DateTime::createFromFormat('d/m/Y', $posted_date);

                            $date_meta = array_merge($date_meta, array(
                                'value' => array($date->format('Y-m-d'), $date->format('Y-m-d')),
                                'compare' => 'BETWEEN'
                                )
                            );
                        }

                        $args['meta_query'][] = $date_meta;
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
                        <div class="programs">
                            <?php while ( $query->have_posts() ) :
                                $query->the_post();
                                ?>
                
                                <div class="program">
                                    <figure>
                                        <a href="<?php the_permalink(); ?>">
                                            <?= wp_get_attachment_image(get_field('image_de_levenement'), 'medium'); ?>
                                        </a>
                                    </figure>
                                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <h3 class="artist">
                                        <?php
                                        $artists_name = '';
                                        foreach(get_field('artiste') as $artist){
                                            $artists_name .= get_the_title($artist).', ';
                                        }
                                        $artists_name = trim($artists_name, ', ');
                                        $pos = strrpos($artists_name, ',');

                                        if($pos !== false){
                                            $artists_name = substr_replace($artists_name, ' '.__('and', 'site-theme'), $pos, strlen(','));
                                        }

                                        echo $artists_name;
                                        ?>
                                    </h3>
                                    <p class="date"><?php the_field('date_et_heure'); ?></p>
                                    <?= truncate(get_field('resume'), 150, "&hellip;", true); ?>
                                    <a class="readmore" href="<?php the_permalink(); ?>"><?php _e('Read more', 'site-theme'); ?></a>
                                </div>

                            <?php endwhile; ?>
                        </div>
                        <?php get_template_part('parts/program', 'pager'); ?>

                    <?php else: ?>
                        <p><?php _e('No program found.', 'site-theme'); ?></p>
                    <?php endif; ?>
            
                    <?php
                    $wp_query = null;
                    $wp_query = $tmp_query;
                    wp_reset_postdata(); ?>
			</div>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>