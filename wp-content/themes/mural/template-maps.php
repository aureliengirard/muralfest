<?php
/**
 * Template Name: Carte dynamique
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" class="site-content">
        <?php get_template_part('parts/inc', 'banner'); ?>
        
		<div class="content-wrap">
            <section class="bgcolor-white">
                <section class="basic-content">
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </section>
            </section>
            
            <div id="gmap-arts"></div>

            <section class="artwork-list">
               <h2><?php _e('Works list','mural'); ?></h2>
                <div class="content">
                    <section class="artwork-col">
                        <?php foreach(Festival()->artworks->get_artworks() as $id => $artwork){
                            echo '<div class="artwork"><span data-markerid="'.$id.'">'.$artwork['title'].'</span></div>';
                        }; ?>
                    </section>
                </div>
            </section>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>