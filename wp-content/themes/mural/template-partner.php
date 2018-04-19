<?php
/**
 * Template Name: Partenaire
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<?php get_template_part('parts/inc', 'banner'); ?>

		<div class="content-wrap">
			<div class="content">
				<? the_content(); ?>

				<div class="partners">
					<?php $tiers = get_terms( array(
						'taxonomy' => 'tier',
						'orderby' => 'term_order',
						'order' => 'ASC'
					) );
					
					foreach($tiers as $tier){
						$logo_width = get_field('largeur', 'tier_'.$tier->term_id);

						$args = array(
							'post_type' => array( 'partner' ),
							'posts_per_page' => '-1',
							'tax_query' => array(
								array(
									'taxonomy' => 'tier',
									'field'    => 'term_id',
									'terms'    => $tier->term_id,
								),
							)
						);
						$query = new WP_Query( $args );
				
						if ( $query->have_posts() ) : ?>
							<div class="tier">
								<?php while ( $query->have_posts() ) :
									$query->the_post();
									
									$website = get_field('site_web');
									$name = get_the_title();
									?>
					
									<figure class="partner">
										<?php if($website): ?>
											<a href="<?= $website ?>" title="<?php printf("View %s's website", $name); ?>" target="_blank">
										<?php endif; ?>

											<?= wp_get_attachment_image( get_field( 'logo' ), 'tier-'.$tier->slug, false, array('alt' => $name) ); ?>
											
										<?php if($website): ?>
											</a>
										<?php endif; ?>
									</figure>

								<?php endwhile; ?>
							</div>
						<?php endif; ?>
				
						<?php wp_reset_postdata();
					} ?>

				</div>
			</div>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>