<?php

/**
 * Template Name: Accueil Temporaire
 *
 */

get_header('temp'); ?>


<?php while (have_posts()) : the_post(); ?>
	<article id="post-temp" class="post-temp content-wrap page type-page status-publish hentry">
		<section class="appels_a_laction">
			<div class="content">
				<div class="cta-wrapper">

					<div class="cta">
						<figure>
							<?php echo wp_get_attachment_image(9452, 'full'); ?>
						</figure>
					</div>
					<div class="cta">
						<figure>
							<?php echo wp_get_attachment_image(9454, 'full'); ?>
						</figure>
					</div>
					<div class="cta">
						<figure>
							<?php echo wp_get_attachment_image(9456, 'full'); ?>
						</figure>
					</div>

				</div>
				<div class="cta-wrapper">

					<div class="cta link-cta">
						<p>Plus de détails à venir. <a href="mailto:info@muralfestival.com"><span>Nous rejoindre</span></a></p>

					</div>

					<div class="cta link-cta">
						<figure>
							<p>More details to come. <a href="mailto:info@muralfestival.com">To reach us</a></p>
						</figure>
					</div>
				</div>
			</div>
		</section>
	</article>
<?php endwhile; ?>


<?php get_footer('temp'); ?>