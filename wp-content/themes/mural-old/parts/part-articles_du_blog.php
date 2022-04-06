<div class="content">
    <?php if(is_singular('post')): ?>
    <h2><?php _e('You may also like', 'site-theme'); ?></h2>
    <?php else : ?>
    <h2><?php _e('Latest posts', 'site-theme'); ?></h2>
    <?php endif; ?>
    <?php
    $args = array(
        'post_type' => array( 'post' ),
        'posts_per_page' => 3
    );

    if(is_singular('post')){
        $category_obj = get_the_category();
        $categories = array();
        foreach($category_obj as $categorie){
            $categories[] = $categorie->cat_ID;
            
        }
        $args = array(
            'post_type' => array( 'post' ),
            'posts_per_page' => 3,
            'category__in' => $categories,
            'orderby'	=> 'rand',
        );
        $args['post__not_in'] = array(get_the_ID());
    }

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) : ?>
        <div class="articles">
            <?php while ( $query->have_posts() ) :
                $query->the_post(); ?>

                <?php get_template_part('parts/blog', 'article'); ?>

            <?php endwhile; ?>
        </div>

        <div class="button-wrap">
            <a href="<?= get_the_permalink(get_posttype_parent_id('post')); ?>" class="button"><?php _e('View +', 'site-theme'); ?></a>
        </div>
    <?php endif; ?>

    <?php 
    wp_reset_postdata(); ?>
</div>