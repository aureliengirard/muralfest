<?php 
/**
 * Class Wholesale_Product_Manager
 * Description: Manage the wholesale option and discount for the products
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

if ( ! class_exists( 'Wholesale_Product_Manager' ) ) :
	
final class Wholesale_Product_Manager {
	
	// Static instance of the class
	protected static $_instance = null;
	
	
	/**
	 * Main Wholesale_Product_Manager Instance
	 *
	 * Ensures only one instance of Wholesale_Product_Manager is loaded or can be loaded.
	 *
	 * @static
	 * @return Wholesale_Product_Manager - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Wholesale_Product_Manager Constructor (called on wp init).
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}
	
	
	
	/**
	 * Wholesale_Product_Manager necessary modules
	 */
	private function includes(){
		
	}//
	
	
	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_discount_metabox' ) );
		add_action( 'save_post', array( $this, 'save_ws_discount_meta'), 1, 2);
	}//
	
	
	
	/**
	 * Return the product dicount percentage if one
	 */
	public function get_product_discount( $post_id = null ){
		if(!$post_id){ return false; }
		
		$discount = get_post_meta($post_id, 'wholesale_rebate', true );
		if(!$discount){
			$discount = 0;
		}
		return $discount;
	}//
	
	
	
	
	/**
	 * Register la meta box pour les options produits
	 */
	public function add_discount_metabox(){
		add_meta_box(
			'ws_product_discount',
			__('Wholesale', 'custom_theme'),
			array( $this, 'meta_box_display' ),
			'product',
			'normal',
			'default'
		);
	}// 
	
	
	
	/**
	 * Display the meta box content
	 */
	public function meta_box_display(){
		global $post;
		
		$current = get_post_meta($post->ID, 'wholesale_rebate', true );
		
		echo '<table class="form-table">';
		 echo '<tr>';
			echo '<th><label for="wholesale_rebate">'. __('Wholesale User Discount', 'custom_theme') .'</label></th>';
			echo '<td>';
				echo '<select id="wholesale_product_rebate" name="wholesale_rebate">';
				
				for ( $i = 0; $i <= 99; $i++ ) {
					printf( '<option value="%1$s" %2$s>%1$s</option>', $i, selected( $current, $i, false ) );
				}

				echo '</select> %';
			echo '</td>';
		 echo '</tr>';
		echo '</table>';
	}// 
	
	
	
	/**
	 * Saves the custom meta value
	 */
	public function save_ws_discount_meta($post_id, $post){
		if ( !current_user_can( 'edit_post', $post->ID )){
			return $post->ID;
		}
		
		if ( !is_admin() ) {
			return $post->ID;
		}
		
		if(isset($_POST['wholesale_rebate'])){
			$value = $_POST['wholesale_rebate'];
			$value = max(0, min(100, intval( $value ) ));
			update_post_meta($post->ID, 'wholesale_rebate', $value);
		}
	}//
	
	
	
	
}// Wholesale_Product_Manager Class

endif;
?>