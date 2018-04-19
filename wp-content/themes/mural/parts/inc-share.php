<? global $post; ?>
<div class="sharing">
	<p><?php _e('Share:', 'site-theme'); ?></p>
	<a href="https://www.facebook.com/sharer/sharer.php?u=<?= get_permalink(); ?>" target="_blank">
		<i class="fab fa-facebook-f" aria-hidden="true"></i>
	</a>
	<a href="https://twitter.com/intent/tweet?source=webclient&amp;original_referer=<?= get_permalink(); ?>&amp;text=<?= get_the_title($post->ID) ?>&amp;url=<?= get_permalink(); ?>" target="_blank">
		<i class="fab fa-twitter" aria-hidden="true"></i>
	</a>
	<a href="https://plus.google.com/share?url=<?= get_permalink(); ?>" target="_blank">
		<i class="fab fa-google-plus-g" aria-hidden="true"></i>
	</a>
	<a href="http://pinterest.com/pin/create/bookmarklet/?media=<?= wp_get_attachment_url( get_post_thumbnail_id() ) ?>&amp;url=<?= get_permalink(); ?>&amp;title=<?= get_the_title($post->ID) ?>&amp;description=<?= get_the_title($post->ID) ?>" target="_blank">
		<i class="fab fa-pinterest-p" aria-hidden="true"></i>
	</a>
	<a href="mailto:?subject=<?= get_the_title($post->ID); ?>&amp;body=<?= get_the_title($post->ID).'%0D%0A'.get_permalink($post->ID) ?>" target="_blank">
		<i class="fa fa-envelope" aria-hidden="true"></i>
	</a>
</div>