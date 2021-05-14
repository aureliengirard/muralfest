<?php
/**
 * The template for displaying Category pages
*/

get_header(); ?>

	<div id="content" class="page-blog" role="main">
		<article id="page-tags" class="site-content">
			<?php get_template_part('parts/inc', 'banner'); ?>

			<div class="content-wrap">
				<section class="basic-content">
					<div class="col-wrapper">
						<div class="left-col">
							<?php if ( have_posts() ) : ?>
								<div class="articles">
									<?php while ( have_posts() ) : the_post(); ?>
										<?php get_template_part('parts/blog', 'article'); ?>
										
									<?php endwhile; ?>
								</div>
								
								<?php get_template_part('parts/blog', 'pager'); ?>
								
							<?php else: ?>
								<section class="no-content">
									<div class="content">
										<p><?php _e('No post found.', 'site-theme'); ?></p>
									</div>
								</section>
							<?php endif; ?>
							
						</div>

						<!--<div class="right-col">
							<?php get_sidebar(); ?>
						</div>-->
					</div>
				</section>
			</div>
			
		</article>
	</div><!-- #content -->

<?php get_footer(); ?>