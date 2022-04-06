<?php
/**
 * The template for displaying Search Results pages
 *
 */

get_header(); ?>

<header>
	<h1>
		<? printf( __( 'Search results for: %s', 'site-theme' ), get_search_query() ); ?>
	</h1>
</header>

<div>
	<div class="content-wrap">
		<?php if ( have_posts() ) : ?>
		
			<?php while ( have_posts() ) : the_post(); ?>
				<article>
					<h1><? the_title(); ?></h1>
				</article>
			<?php endwhile; ?>
		
		<?php else :
			printf( __( 'No result for %s.', 'site-theme' ), get_search_query() );
		endif; ?>
	
	</div>
</div>

<?php get_footer(); ?>