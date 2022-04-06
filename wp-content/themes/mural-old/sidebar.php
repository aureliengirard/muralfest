<?php
/**
 * Main Sidebar
*/
?>

<aside id="sidebar">
	<div class="content">
		<h3><?php _e('Categories', 'site-theme'); ?></h3>
		<ul class="list-categories">
			<?php wp_list_categories(array(
				'title_li' => ''
			)); ?>
		</ul>
	</div>
	
	<?php
	if(get_field('image_de_fond_sidebar', 'options')){
		echo wp_get_attachment_image(get_field('image_de_fond_sidebar', 'options'), 'full', false, array('class' => 'img-parallax'));
	}
	?>
</aside>