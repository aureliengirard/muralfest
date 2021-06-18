<?php if ( have_rows( 'questions_et_reponses' ) ) : ?>
	<div class="questions-reponses">
		<?php while ( have_rows( 'questions_et_reponses' ) ) : the_row(); ?>
			<div class="question-reponse">
				<div class="content">
					<div class="question">
						<h3><?php _e('Q:', 'custom_theme'); ?></h3>
						<?php the_sub_field( 'question' ); ?>
					</div>
					<div class="reponse">
						<h3><?php _e('A:', 'custom_theme'); ?></h3>
						<?php the_sub_field( 'reponse' ); ?>
					</div>
					<span class="toggle-reponse"><span>+</span></span>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
<?php endif; ?>