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
               <h2><?php _e('Works list', 'site-theme'); ?></h2>
                <div class="content">
                    <?php
                    $artworks = Festival()->artworks->get_artworks();
                    $artworks_by_years = array();

                    foreach ($artworks as $id => $artwork) {
                        if(!isset($artworks_by_years[$artwork['date']])){
                            $artworks_by_years[$artwork['date']] = array();
                        }

                        $artworks_by_years[$artwork['date']][$id] = $artwork;
                    } ?>
                        
                    <?php foreach($artworks_by_years as $year => $year_artworks): ?>
                        <div class="year-artworks">
                            <h3><?= $year ?></h3>
                            <section class="artwork-col">
                                <?php foreach($year_artworks as $id => $artwork): ?>
                                    <div class="artwork">
                                        <span data-markerid="<?= $id ?>"><?= $artwork['title'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </section>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>