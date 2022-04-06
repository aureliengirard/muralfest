<?php
/**
 * The template for displaying Tag pages
 *
*/

get_header(); ?>


<?php if ( have_posts() ) : ?>
	<header class="archive-header">
		<h1 class="archive-title"><?php printf( __( 'Tag Archives: %s', 'site-theme' ), single_tag_title( '', false ) ); ?></h1>

		<?php if ( tag_description() ) : // Show an optional tag description ?>
		<div class="archive-meta"><?php echo tag_description(); ?></div>
		<?php endif; ?>
	</header><!-- .archive-header -->
	
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