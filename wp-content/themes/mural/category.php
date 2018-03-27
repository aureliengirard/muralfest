<?php
/**
 * The template for displaying Category pages
*/

global $wp_query;


get_header(); ?>


<?php if ( have_posts() ) : ?>
	<header>
		<h1><?= single_cat_title( '', false ); ?></h1>
		
		<?= HLP()->breadcrumbs; ?>
	</header>
	
	<div class="content-wrap">
		<div class="item-list">
		
			<div class="filters">
				<div>
					<?
					$terms = get_terms( array(
					    'taxonomy' => 'category',
					) );
					?>
					<select id="filter-cats">
						<option value=""><? _e('Filter by categories', 'site-theme'); ?></option>
						<option value="<? the_permalink(get_field('lien_blogue', 'option')); ?>"><? _e('All', 'site-theme'); ?></option>
						<? foreach($terms as $term){
							if($term->parent != 0){
								$name = '- '.$term->name;
							}else{ $name = $term->name; }
							
							$sel = ($wp_query->query_vars['category_name']==$term->slug?' selected="selected"':'');
							echo '<option value="'.esc_url(get_category_link( $term->term_id )).'"'.$sel.'>'.$name.'</option>';
						} ?>
					</select>
				</div>
				
			</div>
			
			<? while ( have_posts() ) : the_post(); ?>
				<article>
					<figure>
						<? $image = get_field( 'image' );
		
						if($image){
							echo wp_get_attachment_image( $image['ID'], 'original' );
						}else{
							the_post_thumbnail();
						}
						?>
					</figure>
					<div>
						<h2><? the_title(); ?></h2>
						
						<div class="metas c12">
							<div class="c6">
								<h4><?= HLP()->tD(get_the_date('j F H')); ?></h4>
							</div>
							<div class="c6">
								<div class="actions">
									<a class="details" href="<?= get_permalink(); ?>"><? _e('Read more', 'site-theme'); ?></a>
								</div>
							</div>
						</div>
					</div>
				</article>
			<? endwhile; ?>
		</div>
	
		<? wp_pagenavi( ); ?>
	</div>
		
	<? get_sidebar(); ?>
	
<?php endif; ?>

<?php get_footer(); ?>