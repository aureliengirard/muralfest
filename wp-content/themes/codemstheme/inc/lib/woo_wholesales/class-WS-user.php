<?php 
/**
 * Class Wholesale_User
 * Description: Manage the wholesale user options
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

if ( ! class_exists( 'Wholesale_User' ) ) :
	
final class Wholesale_User {
	
	// rebate basic percentage for the wholesale customers
	private $_default_rebate = 0;
	
	private $_role = 'wholesale_customer';
	
	// Static instance of the class
	protected static $_instance = null;
	
	
	/**
	 * Main Wholesale_User Instance
	 *
	 * Ensures only one instance of Wholesale_User is loaded or can be loaded.
	 *
	 * @static
	 * @return Wholesale_User - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Wholesale_User Constructor (called on wp init).
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}
	
	
	/**
	 * Getter
	 */
	public function __get( $property ){
		if (property_exists($this, $property)) {
    		return $this->$property;
    	}
	}//
	
	
	
	/**
	 * Wholesale_User necessary modules
	 */
	private function includes(){
		
	}//
	
	
	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'register_role' ) );
		add_action( 'init', array( $this, 'grab_settings' ) );
		
		// User option
		add_action( 'show_user_profile', array( $this, 'additional_profile_fields' ), 50 );
		add_action( 'edit_user_profile', array( $this, 'additional_profile_fields' ), 50 );
		
		// User save
		add_action( 'personal_options_update', array( $this, 'save_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_profile_fields' ) );

	}//
	
	
	
	/**
	 * Return the rebate to apply depending of the user
	 *
	 * @return 0-100 int : representing the % of discount
	 */
	public function get_current_user_rebate(){
		$discount = 0;
		if (!is_user_logged_in()) return $discount;
		$user = wp_get_current_user();
		
		// Only for the wholesale customers
		if ( !in_array( $this->_role, (array)$user->roles ) ) {
			return $discount;
		}
		
		$basicPercentage = $this->_default_rebate;
		$userRebate = intval( get_the_author_meta( 'rebate_override', $user->ID ) );
		
		$discount = ( $userRebate!==0 ? $userRebate : $basicPercentage );
		$discount = max(0, min(100, $discount));	// Clamp between 0 n 100 just in case
		
		return $discount;
	}//
	
	
	
	
	/**
	 * Add new rebate % field above 'Update' button.
	 *
	 * @param WP_User $user User object.
	 */
	public function additional_profile_fields( $user ) {
		// only for the wholesale customers
		if ( !in_array( $this->_role, (array)$user->roles ) ) {
			return;
		}
		
		$current = get_the_author_meta( 'rebate_override', $user->ID );
		
		?>
		<h3><? _e('Wholesale Customer Rebate', 'custom_theme'); ?></h3>

		<table class="form-table">
		<tr>
			<th><label for="rebate_override"><? _e('Rebate', 'custom_theme'); ?></label></th>
			<td>
				<select id="wholesale_user_rebate" name="rebate_override">
					<option value="default"><? _e('Use default rate', 'custom_theme'); ?></option>
				<?
					for ( $i = 1; $i <= 99; $i++ ) {
						printf( '<option value="%1$s" %2$s>%1$s</option>', $i, selected( $current, $i, false ) );
					}
				?></select> %
				
				<br/>
				<span><?= sprintf( __('This value will override the default %s%% rebate for this customer.', 'custom_theme'),
					(string)$this->_default_rebate
				); ?></span>
				 
			</td>
		</tr>
		</table>
		<?php
	}//
	
	
	
	
	/**
	 * Save additional profile fields.
	 *
	 * @param  int $user_id Current user ID.
	 */
	function save_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		if ( empty( $_POST['rebate_override'] ) ) {
			return false;
		}
		
		// If set to none, erase value
		$value = $_POST['rebate_override'];
		
		if( $value == 'default' ){
			delete_user_meta( $user_id, 'rebate_override' );
		}else{
			update_usermeta( $user_id, 'rebate_override', $value );
		}
		
	}//
	
	
	
	
	
	/**
	 * Register necesary hooks
	 */
	public function register_role(){
		// Run only once
		if( HLP()->run_once('WS_register_user_role') ){
			global $wp_roles;
			if ( ! isset( $wp_roles ) )
				$wp_roles = new WP_Roles();

			$customer = $wp_roles->get_role('customer');
			
			if($customer){
				$wp_roles->add_role( $this->_role, 'Wholesale User', $customer->capabilities);
			}
		}
	}//
	
	
	
	/**
	 * Grab the user default settings
	 */
	public function grab_settings(){
		// Default to 0
		if( $rate = get_field( 'wholesale_basic_user_discount', 'option' ) ){
			$this->_default_rebate = intval($rate);
		}
	}//
	
	
}// Wholesale_User Class

endif;
?>