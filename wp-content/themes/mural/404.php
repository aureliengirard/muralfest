<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
*/

get_header(); ?>

<article id="post-404" class="site-content">
	
	<div class="content-wrap">
		<section class="page-404">
			<div class="content">
				<h1>404 !</h1>
				<div class="like-h4"><? _e( 'Oups! This is not the page you are looking for.', 'site-theme' ); ?></div>
				<p>
					<a class="button btn" href="<?= home_url( '/' ); ?>"><? _e('Back to homepage', 'site-theme'); ?></a>
				</p>
			</div>
		</section>
	</div>

</article><!-- #post -->

<?php get_footer(); ?>