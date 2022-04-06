<?php
if ( have_rows( 'contenu_avec_fond' ) ){
	while ( have_rows('contenu_avec_fond' ) ) { the_row();
		$classes = 'bgcolor-'.get_sub_field('couleur');
		if(get_sub_field('ajouter_une_image')){
			$classes .= ' has-background-image';

			if(get_sub_field('ajouter_du_parallax')){
				$classes .= ' background-parallax';
			}
		}

		echo '<section class="'.$classes.'">';

		get_template_part('parts/inc', 'content');

		if(get_sub_field('ajouter_une_image')){
			$img_attr = array();
	
			if(get_sub_field('ajouter_du_parallax')){
				$img_attr['class'] = 'img-parallax';
			}else{
				$img_attr['class'] = 'background-image';
			}
	
			echo wp_get_attachment_image(get_sub_field('image_de_fond'), 'full', false, $img_attr);
		}

		echo '</section>';
	}
}