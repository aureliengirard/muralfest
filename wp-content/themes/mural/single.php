<?php
/**
 * The template for displaying all singles
*/

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" class="site-content c12">
		
		<?= displayBackBtn(); ?>
		
		<div class="c12">
			<div class="content-wrap c8">
				<figure class="c5">
					<? $image = get_field( 'image' );
					
					if($image){
						echo wp_get_attachment_image( $image['ID'], 'original' );
					}else{
						the_post_thumbnail();
					}
					?>
				</figure>
				<div class="c7">
					<h1><? the_title(); ?></h1>
					
					<? get_template_part('parts/inc', 'share'); ?>
					<? the_content(); ?>
				</div>
				
			</div>
			
			<? get_sidebar(); ?>
		</div>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>