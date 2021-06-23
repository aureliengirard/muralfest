
<?php if(get_field('facebook', 'option')): ?>
	<li><a href="<? the_field('facebook', 'option'); ?>" target="_blank"><i class="fab fa-facebook"></i></a></li>
<?php endif; ?>
<?php if(get_field('instagram', 'option')): ?>
	<li><a href="<? the_field('instagram', 'option'); ?>" target="_blank"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('twitter', 'option')): ?>
	<li><a href="<? the_field('twitter', 'option'); ?>" target="_blank"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('google_plus', 'option')): ?>
	<li><a href="<? the_field('google_plus', 'option'); ?>" target="_blank"><i class="fab fa-google-plus" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('linkedin', 'option')): ?>
	<li><a href="<? the_field('linkedin', 'option'); ?>" target="_blank"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
<?php endif; ?>



<?php if(get_field('youtube', 'option')): ?>
	<li><a href="<? the_field('youtube', 'option'); ?>" target="_blank"><i class="fab fa-youtube" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('snapchat', 'option')): ?>
	<li><a href="<? the_field('snapchat', 'option'); ?>" target="_blank"><i class="fab fa-snapchat-ghost" aria-hidden="true"></i></a></li>
<?php endif; ?>

<?php if(get_field('soundcloud', 'option')): ?>
	<li><a href="<? the_field('soundcloud', 'option'); ?>" target="_blank"><i class="fab fa-soundcloud" aria-hidden="true"></i></a></li>
<?php endif; ?>