<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 */
?>

		</main>
		
		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="content">
				<div class="site-info">
					<div class="legal">
						&copy; Copyright <?= date('Y'); ?>
						<? _e('All rights reserved.', 'site-theme') ?>
					</div>
				</div><!-- .site-info -->
			</div>
			
		</footer><!-- #colophon -->
		
	</div><!-- #page -->

	<?php wp_footer(); ?>
</div> <!--pour mmenu-->
</body>
</html>