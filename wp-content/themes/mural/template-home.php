<?php
/**
 * Template Name: Accueil
 *
 */

get_header(); ?>
	

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content">
		<div class="banner">
			<figure>
				<?php echo wp_get_attachment_image( get_field( 'banniere' ), 'max-banner' ); ?>
				<?php if(get_field('contenu_banniere')): ?>
					<div class="banner-overlay">
						<?php the_field('contenu_banniere'); ?>
					</div>
				<?php endif; ?>
			</figure>
		</div>
		<div class="content-wrap">
			<?php get_template_part('parts/inc', 'content'); ?>
		</div>
	</article>
<?php endwhile; ?>


<?php get_footer(); ?>