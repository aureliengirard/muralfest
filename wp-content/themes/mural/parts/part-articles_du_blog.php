<div class="content">
    <h2><?php _e('Latest posts', 'site-theme'); ?></h2>
    <?php
    $args = array(
        'post_type' => array( 'post' ),
        'posts_per_page' => 3
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) : ?>
        <div class="articles">
            <?php while ( $query->have_posts() ) :
                $query->the_post(); ?>

                <?php get_template_part('parts/blog', 'article'); ?>

            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <?php 
    wp_reset_postdata(); ?>
</div>