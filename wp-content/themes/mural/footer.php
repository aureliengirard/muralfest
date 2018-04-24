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
				<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<?php echo wp_get_attachment_image( get_field( 'logo_blanc', 'options' ), 'original' ); ?>
				</a>

				<ul class="sociaux">
					<?php get_template_part('parts/inc', 'sociaux'); ?>
				</ul>

				<nav class="navigation footer-navigation" role="navigation">
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'depth' => 1, 'walker' => new MenuWalker() ) ); ?>
				</nav>
			</div>
		</footer><!-- #colophon -->
		
	</div><!-- #page -->

	<?php wp_footer(); ?>	
</div> <!--pour mmenu-->
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