<?php
/**
 * The template for displaying image attachments
 *
*/

//// DISABLED
// Redirige vers le post de l'attachement.

wp_redirect( get_permalink( $post->post_parent ) );
exit;
?>