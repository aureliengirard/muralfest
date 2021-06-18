<?php
/**
 * Class Avatar
 * Description: Allow custom user avatar
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.6
 *
 *
 * @category Core
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Avatar' ) ) :

final class Avatar {

	// Static instance of the class
	protected static $_instance = null;


	/**
	 * Main Avatar Instance
	 *
	 * Ensures only one instance of Avatar is loaded or can be loaded.
	 *
	 * @static
	 * @return Avatar - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Avatar Constructor.
	 */
	private function __construct() {
		$this->init_hooks();
		$this->includes();
	}

	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ), 1 );

		add_action( 'wp_enqueue_scripts', array( $this, 'avatar_registerScripts' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'avatar_sendDataJS' ), 30 );

		add_filter('get_avatar', array($this, 'use_custom_avatar'), 10, 5);

	}


	/**
	 * Include required core files
	 */
	public function includes() {
		// Crop class
		include_once( __DIR__.'/inc/crop.class.php' );

	}


	/**
	 * Init Avatar when WordPress Initialises.
	 */
	public function init() {
		if(isset($_POST) && !empty($_POST['cropData']) && is_user_logged_in()){
			$this->manageAvatar('user_'.get_current_user_id());
		}

	}// init



	/**
	 * Manage the image and save it
	 */
	public function manageAvatar($post_id){
		if(substr($post_id, 0, 5) == 'user_' && !empty($_POST['cropData'])){
			$user_id = str_replace('user_', '', $post_id);

			// Grap and convert data
			$cropData = json_decode( stripslashes($_POST['cropData']) );

			// rebuild $_FILES data for the class
			$newFile = array();

			if(!empty($_FILES['acf'])){
				foreach($_FILES['acf'] as $key => $param){
					$newFile[$key] = $param['field_58221368ab046'];
				}

				$crop = new CropAvatar(
					isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
					isset($_POST['cropData']) ? $_POST['cropData'] : null,
					isset($newFile) ? $newFile : null
				);

				$response = array(
					'state'  => 200,
					'message' => $crop -> getMsg(),
					'result' => $crop -> getResult(),
					'filename' => $crop -> getFilename(),
				);

				// The ID of the post this attachment is for.
				$parent_post_id = $user_id;

				// Check the type of file. We'll use this as the 'post_mime_type'.
				$filetype = wp_check_filetype( basename( $response['result'] ), null );

				// Get the path to the upload directory.
				$wp_upload_dir = wp_upload_dir();

				// Prepare an array of post data for the attachment.
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/userpics/' . basename( $response['result'] ),
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $response['result'] ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Insert the attachment.
				$attach_id = wp_insert_attachment( $attachment, $wp_upload_dir['basedir'].'/userpics/'.basename($response['result']), $parent_post_id );

				// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $wp_upload_dir['basedir'].'/userpics/'.basename($response['result']) );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				// If image trop petite
				if(intval($attach_data['width']) < 200 || intval($attach_data['height']) < 200 ){
					return __('Image is too small.', 'custom_theme');
				}


				// Updates the ACF Image field of the user

				update_field('field_58221368ab046', $attach_id, 'user_'.$user_id);


				// Unset the acf to prevent normal save of the original value
				//var_dump($_POST["acf"]);

				unset($_POST['acf']['field_58221368ab046']);

				if(!empty($parent_post_id)){

					$image_query = new WP_Query(
						array(
							'post_type' => 'attachment',
							'post_status' => 'inherit',
							'post_mime_type' => 'image',
							'posts_per_page' => -1,
							'post_parent' => $parent_post_id,
							'order' => 'DESC'
						)
					);

					$skipLast=0;
					if( $image_query->have_posts() ){
						while( $image_query->have_posts() ) {
							$image_query->the_post();
							if($skipLast > 0){
								// Delete all other image related to this user
								$force_delete = true;
								wp_delete_attachment( get_the_ID(), $force_delete );
							}
							$skipLast++;
						}
					}
				}
			}
			wp_reset_postdata();

		}// user save
	}// manageAvatar


	/**
	 * Use the custom field instead of the default wp
	 */
	public function use_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
	    // Get user by id or email
	    if ( is_numeric( $id_or_email ) ) {
	        $id   = (int) $id_or_email;
	        $user = get_user_by( 'id' , $id );

	    } elseif ( is_object( $id_or_email ) ) {
	        if ( ! empty( $id_or_email->user_id ) ) {
	            $id   = (int) $id_or_email->user_id;
	            $user = get_user_by( 'id' , $id );
	        }

	    } else {
	        $user = get_user_by( 'email', $id_or_email );
	    }

	    if ( ! $user ) {
	        return $avatar;
	    }

	    // Get the user id
	    $user_id = $user->ID;

	    // Get the file id
	    $image_id = get_user_meta($user_id, 'avatar', true); // CHANGE TO YOUR FIELD NAME

	    // Bail if we don't have a local avatar
	    if ( ! $image_id ) {
	        return $avatar;
	    }

	    // Get the img
	    $image_url = wp_get_attachment_image(
	    	$image_id,
	    	array($size, $size),
	    	"",
	    	array('class' => 'avatar avatar-' . $size . '', 'alt' => $alt)
	    ); // Set image size by name

	    // Get the img markup
	    $avatar = $image_url;

	    return $avatar;
	}// use_custom_avatar



	/**
	 * get the HTML for the field (customizable)
	 */
	public function get_avatar_HTML(){
		if(!is_user_logged_in()){ return; }

		ob_start(); ?>

		<form method="post" class="avatar_form" enctype="multipart/form-data">
			<?
			require_once( __DIR__.'/inc/popup-cropAvatar.php' );

			$userID = get_current_user_id();
			$field = get_field_object('field_58221368ab046', 'user_'.$userID);
			if($field): ?>
				<label>
					<?= $field['label']; ?><?= ($field['required']?'<span class="required">*</span>':'') ?>
				</label>
				<span class="smaller"><?= $field['instructions'] ?></span>
				<span class="preview-avatar"><?= get_avatar($userID, 150, '', $user->display_name); ?></span>
				<input type="file" name="acf[<?= $field['key'] ?>]" value="" class="upload-avatar">

			<?php /*endif;*/ ?>
			<input type="submit" class="button" value="<? _e( 'Save image', 'custom_theme' ); ?>" />
			<input type="hidden" name="cropData" value="" />

		</form>

		<?php
		echo ob_get_clean();
	}// get_avatar_HTML


	/**
	 * Register required scripts
	 */
	public function avatar_registerScripts(){
		wp_enqueue_style("cropperCSS", THEMEURI."/inc/lib/user_avatar/assets/css/cropper.min.css" , false, "1.0");
		wp_enqueue_style("avatarCSS", THEMEURI."/inc/lib/user_avatar/assets/css/profil-image.css" , false, "1.0");


		wp_enqueue_script("cropperJS", THEMEURI."/inc/lib/user_avatar/assets/js/cropper.min.js" , false, "1.0", true);
		wp_enqueue_script("avatarJS", THEMEURI."/inc/lib/user_avatar/assets/js/profil-image.js" , array('cropperJS'), "1.0", true);

	}//



	/**
	 * Sends some php data to the JS script
	 */
	public function avatar_sendDataJS(){
		// Localize the script with new data
		$avatarData = array(
			'themeURI' => THEMEURI
		);

		wp_localize_script( 'avatarJS', 'avatarData', $avatarData );
	}//

}// Avatar Class

endif;


function Avatar() {
	return Avatar::instance();
}

// Accessible aussi par une global
$GLOBALS['Avatar'] = Avatar();

?>