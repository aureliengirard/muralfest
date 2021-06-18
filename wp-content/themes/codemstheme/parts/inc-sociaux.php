<?php if(get_field('facebook', 'option')): ?>
	<li><a href="<? the_field('facebook', 'option'); ?>" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('twitter', 'option')): ?>
	<li><a href="<? the_field('twitter', 'option'); ?>" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('google_plus', 'option')): ?>
	<li><a href="<? the_field('google_plus', 'option'); ?>" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('linkedin', 'option')): ?>
	<li><a href="<? the_field('linkedin', 'option'); ?>" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('instagram', 'option')): ?>
	<li><a href="<? the_field('instagram', 'option'); ?>" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('youtube', 'option')): ?>
	<li><a href="<? the_field('youtube', 'option'); ?>" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('snapchat', 'option')): ?>
	<li><a href="<? the_field('snapchat', 'option'); ?>" target="_blank"><i class="fa fa-snapchat-ghost" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php do_action('add_more_socials_media'); ?>