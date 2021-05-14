<? global $post; ?>
<div class="sharing">
	<p><?php _e('Share:', 'site-theme'); ?></p>
	<div class="sharing-item-container">
		
		<iframe src="https://www.facebook.com/plugins/like.php?href=<?= get_permalink(); ?>&width=153&layout=button_count&action=like&size=small&show_faces=false&share=true&height=46&appId" width="140" height="46" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
		
		
		<div class="icon-twitter-wrapper">
			<a class="twitter-share-button" href="https://twitter.com/intent/tweet?source=webclient&amp;original_referer=<?= get_permalink(); ?>&amp;text=<?= get_the_title($post->ID) ?>&amp;url=<?= get_permalink(); ?>" target="_blank">Tweet</a>
		</div>
		<a href="mailto:?subject=<?= get_the_title($post->ID); ?>&amp;body=<?= get_the_title($post->ID).'%0D%0A'.get_permalink($post->ID) ?>" target="_blank">
			<i class="fa fa-envelope" aria-hidden="true"></i>
		</a>
	</div>
	
</div>
