<?php
/**
 * The template for displaying all singles
*/
$categories =  get_the_category();

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>">

		<div class="content-wrap">
            <section class="basic-content">
                <div class="content">
                    <section class="single-navigation">
                        <?php display_back_button(); ?>

                        <?php displayBreadcrumb(); ?>
                    </section>

                    <div class="content-blog">
                        <figure>
                            <?= wp_get_attachment_image(get_field('image_a_la_une'), 'original'); ?>
                        </figure>
                        
                        <h1><?php the_title(); ?></h1>
                        <p class="date"><?= get_the_date('j F Y'); ?></p>
                        <p class="post-categories"><?php _e('categories','site-theme') ?> : <?php 
                        foreach($categories as $categorie){
                            $name=$categorie->name.' ';
                            $link = get_category_link($categorie);
                            echo '<a href='.$link.'>'.$name.'</a>';
                        } ?></p>
                        
                        <!--<div class="blog-categories">
                            <?= get_the_category_list( ', ', '', get_the_ID() ); ?>
                        </div>-->

                        <?php get_template_part('parts/inc', 'background_content'); ?>
                    
                        <?php get_template_part('parts/inc', 'share'); ?>
                        

                        <section class="articles_du_blog">
                            <?php get_template_part('parts/part', 'articles_du_blog'); ?>
                        </section>
                    </div>
                </div>
            </section>
        </div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>