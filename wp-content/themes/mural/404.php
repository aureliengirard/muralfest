<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
*/

get_header(); ?>

<article id="post-404" class="site-content">
	
	<div class="content-wrap">
		<div class="content">
			<h1>404 !</h1>
			<div class="like-h2"><? _e( 'Oups! This is not the page you are looking for.', 'site-theme' ); ?></div>
			<p>
				<a class="button btn" href="<?= home_url( '/' ); ?>"><? _e('Back to homepage', 'site-theme'); ?></a>
			</p>
		</div>
	</div>

</article><!-- #post -->

<?php get_footer(); ?>