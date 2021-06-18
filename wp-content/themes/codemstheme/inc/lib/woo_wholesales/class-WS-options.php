<?
/**
 * Class WS_options
 * Description: Manage the module options page
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 *
 * @category Woocommerce
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( ! class_exists( 'WS_options' ) ) :
	
final class WS_options {
	
	// Static instance of the class
	protected static $_instance = null;
	
	/**
	 * Main WS_options Instance
	 *
	 * Ensures only one instance of WS_options is loaded or can be loaded.
	 *
	 * @static
	 * @return WS_options - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	
	private function __construct(){
		$this->add_required_wholesale_acf_fields();
		
		add_action( 'admin_menu', array( $this, 'create_settings_page' ) );
		add_action( 'admin_init', array( $this, 'add_acf_variables' ) );
	}
	
	
	
	/**
	 * Adds the custom options page
	 */
	public function create_settings_page() {
		// Add the menu item and page
		$page_title = 'Wholesale options';
		$menu_title = 'Wholesale options';
		$capability = 'manage_options';
		$slug = 'wholesale_module_settings';
		$callback = array( $this, 'settings_page_content' );
		$icon = 'dashicons-admin-plugins';
		$position = 100;
		
		
		// First level
		// add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
		
		// Sub-level.. Under Settings
		add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
	}//
	
	
	
	/**
	 * Content of the options page
	 */
	public function settings_page_content(){
		do_action('acf/input/admin_head');				// Add ACF admin head hooks
		do_action('acf/input/admin_enqueue_scripts');	// Add ACF scripts

		$options = array(
			'id' => 'acf-form',
			'post_id' => 'options',
			'new_post' => false,
			'field_groups' => array( 'wholesale_module_acf_settings' ),
			'return' => admin_url('admin.php?page=wholesale_module_settings'),
			'submit_value' => 'Update',
		);
		acf_form( $options );
	}// 
	
	
	public function add_acf_variables() {
		acf_form_head();
	}
	
	
	
	/**
	 * Register the options group to acf for use
	 */
	private function add_required_wholesale_acf_fields(){
		if( function_exists('acf_add_local_field') ):
			acf_add_local_field_group(array (
				'key' => 'wholesale_module_acf_settings',
				'title' => 'Wholesale settings',
				'fields' => array (
					array (
						'key' => 'field_5807c6a6d0708',
						'label' => 'Basic user discount',
						'name' => 'wholesale_basic_user_discount',
						'type' => 'number',
						'instructions' => 'Default % discount rate for every wholesale customer.
			This value will be overwritten if a user has a custom rate.',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => 0,
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'min' => 0,
						'max' => 99,
						'step' => 1,
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'current_user_role',
							'operator' => '==',
							'value' => 'administrator',
						),
						array (
							'param' => 'current_user_role',
							'operator' => '!=',
							'value' => 'administrator',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => 1,
				'description' => '',
			));
		endif;
	}//
	
	
	
}
WS_options::instance();

endif;
?>