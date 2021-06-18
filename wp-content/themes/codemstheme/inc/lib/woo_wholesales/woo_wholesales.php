<?php 
/**
 * Class WOO_wholesales
 * Description: Manage all the wholesales rebate assignements and logic
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

if ( ! class_exists( 'WOO_Wholesales' ) ) :
	
final class WOO_Wholesales {
	
	// Reference to the wholesale modules class
	private $_userClass = null;
	private $_productManager = null;
	
	// Static instance of the class
	protected static $_instance = null;
	
	
	/**
	 * Main WOO_Wholesales Instance
	 *
	 * Ensures only one instance of WOO_Wholesales is loaded or can be loaded.
	 *
	 * @static
	 * @return WOO_Wholesales - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * WOO_Wholesales Constructor (called on wp init).
	 */
	private function __construct() {
		// WooCommerce needs to be active to operate
		if ( class_exists( 'WooCommerce' ) ) {
			$this->includes();
			$this->init_hooks();
		}
	}
	
	
	/**
	 * WOO_Wholesales necessary modules
	 */
	private function includes(){
		include_once( 'class-WS-options.php' );
		include_once( 'class-WS-user.php' );
		include_once( 'class-WS-products.php' );
		
		$this->_productManager = Wholesale_Product_Manager::instance();
		$this->_userClass = Wholesale_User::instance();
	}//
	
	
	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		
		// Tous les filtres pour les endroits ou les reéduction s'applique
		add_filter( 'woocommerce_get_price', array($this, 'apply_price_rebates'), 1, 2 );
		add_filter( 'woocommerce_get_regular_price', array($this, 'apply_price_rebates'), 1, 2 );
		add_filter( 'woocommerce_get_sale_price', array($this, 'apply_price_rebates'), 1, 2 );
		
		// Préférence de listage de produits
		add_action( 'woocommerce_before_shop_loop', array( $this, 'add_showall_button'), 40 );
		add_action( 'woocommerce_product_query', array( $this, 'modify_products_query' ), 50 );
		
		
		// Variations
		// Dont call this yet .. causes bug with cache because values are stored in transients 
		// add_filter( 'woocommerce_variation_prices_price', array( $this, 'apply_variation_price_rebates'), 10, 3 );
		// add_filter( 'woocommerce_variation_prices_regular_price', array( $this, 'apply_variation_price_rebates'), 10, 3 );
		// add_filter( 'woocommerce_variation_prices_sale_price', array( $this, 'apply_variation_price_rebates'), 10, 3 );
		
	}//
	
	
	
	/**
	 * Apply all the rebates to the product price
	 */
	public function apply_price_rebates( $price, $product ){
		if (!is_user_logged_in()) return $price;
		
		// check if the user has the wholesale customer role
		if ( $this->user_has_role( $this->_userClass->__get( '_role' ) ) ){
			// Give user the appropriate rebate
			$rebate = $this->get_total_rebate( $product->id );
			$price = round( $price * $rebate, 2 );			
		}
		return $price;
	}//
	
	
	
	/**
	 * Apply rebate visually on variables product also
	 */
	public function apply_variation_price_rebates( $price, $variation, $product ){
		if (!is_user_logged_in()){
			return $price;
		}
		$price = $this->apply_price_rebates( $price, $product );
		
		return $price;
	}//
	
	
	
	/**
	 * Calculate the total appliable rebate and format it in pourcentage
	 */
	private function get_total_rebate( $product_id ){
		// Get user rebate
		$user_rebate = $this->_userClass->get_current_user_rebate();
		$product_rebate = $this->_productManager->get_product_discount( $product_id );
		
		$total_rebate = ( $user_rebate + $product_rebate );
		
		$percentage = 100;
		$percentage = max(0, min(100, ($percentage - $total_rebate)) );
		$percentage = ($percentage / 100);
		
		return $percentage;
	}//
	
	
	
	
	/**
	 * Register necesary hooks
	 */
	private function user_has_role($role = '', $user_id = null){
		if ( is_numeric( $user_id ) ){
			$user = get_user_by( 'id', $user_id );
		}else{
			$user = wp_get_current_user();
		}

		if ( empty( $user ) ){
			return false;
		}

		return in_array( $role, (array) $user->roles );
	}
	
	
	
	
	/**
	 * Ajoute le bouton dans le haut de la liste des produits
	 */
	public function add_showall_button(){
		if ( $this->user_has_role( $this->_userClass->__get( '_role' ) ) ){
			get_template_part('parts/inc', 'product_showall');
		}
		
		return true;
	}//
	
	
	
	/**
	 * Modify the main query for the wholesales users
	 */
	public function modify_products_query( $q ){
		// Set the current preferences
		if( isset($_GET['switch_ws_display']) && $_GET['switch_ws_display'] !== '' ){
			if( $_GET['switch_ws_display'] == 'all' ){
				$_SESSION['ws_display_pref'] = 'all';
			}elseif( $_GET['switch_ws_display'] == 'limited' ){
				unset( $_SESSION['ws_display_pref'] );
			}
		}
		
				
		$meta_query = $q->get( 'meta_query' );
 
		if ( $this->user_has_role( $this->_userClass->__get( '_role' ) ) ){
			if( $_SESSION['ws_display_pref'] !== 'all' ){
				$meta_query[] = array(
					'key'       => 'wholesale_rebate',
					'value'		=> '0',
					'compare'   => 'NOT LIKE'
				);
			}
		}
		
		$q->set( 'meta_query', $meta_query );
		
	}// 
	
	
}// WOO_Wholesales Class
	
function WOO_Wholesales() {
	return WOO_Wholesales::instance();
}
WOO_Wholesales();

endif;
?>