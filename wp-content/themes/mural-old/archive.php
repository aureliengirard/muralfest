<?php
/**
 * The template for displaying Archive pages
 *
*/

get_header(); ?>

<?php if ( have_posts() ) : ?>
	<header class="archive-header">
		<h1><?php
			if ( is_day() ) :
				printf( __( 'Daily Archives: %s', 'site-theme' ), get_the_date() );
			elseif ( is_month() ) :
				printf( __( 'Monthly Archives: %s', 'site-theme' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'site-theme' ) ) );
			elseif ( is_year() ) :
				printf( __( 'Yearly Archives: %s', 'site-theme' ), get_the_date( _x( 'Y', 'yearly archives date format', 'site-theme' ) ) );
			else :
				_e( 'Archives', 'site-theme' );
			endif;
		?></h1>
	</header><!-- .archive-header -->
	
    
	<?php while ( have_posts() ) : the_post(); ?>
		<article>
        	<header><h2><a href="<? the_permalink(); ?>"><? the_title(); ?></a></h2></header>
            <? the_content(); ?>
        </article>
	<?php endwhile; ?>

	<?php theme_paging_nav(); ?>

<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>