<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
*/

get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php while ( have_posts() ) : the_post(); ?>
		<article>
			<header><h1><a href="<? the_permalink(); ?>"><? the_title(); ?></a></h1></header>
			<? the_content(); ?>
		</article>
	<?php endwhile; ?>

	<?php theme_paging_nav(); ?>

<?php else : ?>
	<?php get_template_part( 'content', 'none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>