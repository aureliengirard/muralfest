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
			<div class="footer-wrapper">
				<figure class="footer-flower">
					<?php echo wp_get_attachment_image( get_field( 'fleur', 'options' ), 'original' ); ?>
				</figure>
				<div class="content">
					<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<?php echo wp_get_attachment_image( get_field( 'logo_blanc', 'options' ), 'original' ); ?>
					</a>
					<div class="info-sup-wrapper">
						<div class="lndmrk-wrapper">
							<p class="footer-lndmrk"><?php _e('created by','site-theme')?></p>
							<?php echo wp_get_attachment_image( get_field( 'logo_lndmrk', 'options' ), 'original' ); ?>
						</div>
						<figure class="boulevard-wrapper">
							<?php echo wp_get_attachment_image( get_field( 'boulevard', 'options' ), 'original' ); ?>
						</figure>
					</div>			
					
					<ul class="sociaux">
						<?php get_template_part('parts/inc', 'sociaux'); ?>
					</ul>

					<nav class="navigation footer-navigation" role="navigation">
						<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'depth' => 1, 'walker' => new MenuWalker() ) ); ?>
					</nav>
					
				</div>
			</div>
		</footer><!-- #colophon -->
		
	</div><!-- #page -->

	<?php wp_footer(); ?>	
</div> <!--pour mmenu-->
<form class="newsletter">
	<div class="popup-wrapper">
		<h3><?php _e('Stay connected!','site-theme') ?>!</h3>
		<p><?php _e('Subscribe to our newsletter to receive exclusive details about the festival and our latest blog posts!','site-theme') ?></p>
		<div class="input-wrap">
			<input type="text" name="email" placeholder="<?php /*translators:courriel*/ _e('Email', 'site-theme'); ?>" />
			<button type="submit" class="button"><?php _e('Submit','site-theme')?></button>
		</div>
		<div class="popup-exit">
			<i class="fas fa-times"></i>
		</div>
	<p class="error-log"></p>
	</div>
</form>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118017697-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118017697-1');
</script>
</body>
</html>