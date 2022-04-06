<div class="content">
    <h2><?php _e('Events', 'site-theme'); ?></h2>
    <?php
    $args = array(
        'post_type' => array( 'program' ),
        'post__in' => get_sub_field('evenements'),
        'posts_per_page' => -1,
        'orderby' => 'post__in'
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) : ?>
        <div class="programs">
            <?php while ( $query->have_posts() ) :
                $query->the_post(); ?>

                <?php get_template_part('parts/program', 'article'); ?>

            <?php endwhile; ?>
        </div>

        <div class="button-wrap">
            <a href="<?= get_the_permalink(Festival()->get_festival_sub_page('program')); ?>" class="button"><?php _e('View all events', 'site-theme'); ?></a>
        </div>
    <?php endif; ?>

    <?php 
    wp_reset_postdata(); ?>
</div>