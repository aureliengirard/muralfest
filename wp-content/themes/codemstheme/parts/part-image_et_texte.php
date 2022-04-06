<div class="content">
	<?php if(get_sub_field( 'titre_section' )): ?>
		<h3><?php the_sub_field( 'titre_section' ); ?></h3>
	<?php endif; ?>
	
	<?php ob_start(); ?>
		<figure class="img-bloc">
			<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'original' ); ?>
		</figure>
	<?php $img_bloc = ob_get_clean(); ?>
	
	<?php if(!get_sub_field( 'img_position' )): ?>
		<?= $img_bloc ?>
	<?php endif; ?>
	
	<div class="texte-bloc">
		<?php the_sub_field( 'texte' ); ?>
	</div>
	
	<?php if(get_sub_field( 'img_position' )): ?>
		<?= $img_bloc ?>
	<?php endif; ?>
</div>