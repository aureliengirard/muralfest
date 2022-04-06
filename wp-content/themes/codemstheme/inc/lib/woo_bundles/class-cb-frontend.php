<?php 
/**
 * Class CB_frontend
 * Description: Manage all the Choices Bundles Behaviors
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

if ( ! class_exists( 'CB_frontend' ) ) :
	
final class CB_frontend {
	
	// Current bundles in the cart
	private $current_bundles = array();
	
	// Conditions that needs to be filled
	private $conditions = array();
	
	
	
	// List of products that fits a condition and will be reduced
	private $affected_products = array();
	
	// List of message to display to the user
	private $messages = array();
	
	
	// Static instance of the class
	protected static $_instance = null;
	
	
	/**
	 * Main CB_frontend Instance
	 *
	 * Ensures only one instance of CB_frontend is loaded or can be loaded.
	 *
	 * @static
	 * @return CB_frontend - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * CB_frontend Constructor (called on wp init).
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}
	
	
	/**
	 * CB_frontend necessary modules
	 */
	private function includes(){
		
	}//
	
	
	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'registerScripts' ), 1 );
		
		add_action('woocommerce_before_calculate_totals', array( $this, 'get_cart_bundles' ) );
		
		add_action( 'woocommerce_before_cart_contents', array( $this, 'display_messages' ) );
		add_action( 'woocommerce_before_main_content', array( $this, 'display_messages' ) );
		// add_action( 'woocommerce_single_product_summary', array( $this, 'display_messages' ) );
		
		add_filter( 'woocommerce_get_item_data', array( $this, 'add_item_meta' ), 20, 2 );
		add_filter( 'woocommerce_cart_item_price', array( $this, 'display_original_price' ), 20, 3 );
	}//
	
	
	
	/**
	 * Add necessary scripts and CSS for the front-end
	 */
	public function registerScripts(){
		wp_enqueue_style( 'choices_bundle_css', THEMEURI.'/inc/lib/woo_bundles/assets/style.css', false, '1.0.0' );
	}// 
	
	
	
	/**
	 * Put bundle product IDs present in cart in a var
	 */
	public function get_cart_bundles( $cart ){
		$this->reset_cart_double_entries( $cart );
		
		$items = $cart->get_cart();
		
		foreach ( $items as $cart_item_key => $cart_item ) {
			if($cart_item['data']->product_type == 'choices_bundle' ){
				
				// Add the bundle quantity too
				$this->current_bundles[$cart_item_key] = $cart_item['quantity'];
				
			}	
		}
		
		// Trigger the conditions management and the rest
		if(!empty( $this->current_bundles )){
			$this->check_conditions();
			
			// Trigger futur messages setup to have acces to the wordpress conditional tags
			add_action( 'wp_head', array( $this, 'setup_notices' ), 1 );
		}
	}//
	
	
	
	/**
	 * Grab the product that will be affected by the bundles
	 */
	private function check_conditions(){
		// Conditions
		foreach($this->current_bundles as $cart_item_key => $qty ){
			$bundle = WC()->cart->cart_contents[$cart_item_key]['data'];
			$conds = $bundle->get_bundle_conditions();
			
			// Make a sum for of the conditions for the same bundle
			for($i=0; $i<$qty; $i++){
				foreach($conds as $cond){
					$this->conditions[$cart_item_key][ $cond['cat'] ] = array(
						'cat'			=>	$cond['cat'],
						'cat_number'	=>	($this->conditions[$cart_item_key][ $cond['cat'] ]['cat_number'] + $cond['cat_number']),
						'max_amount'	=>	$cond['max_amount']
					);
				}
			}
		}
		
		
		
		
		// Loop each bundle
		foreach( $this->conditions as $bundle_cart_key => $conds ){
			
			// Grab items that fits in a condition
			$items = WC()->cart->get_cart();
			foreach ( $items as $cart_item_key => $cart_item ) {
				
				// Grab all the terms id for this post
				$allTerms = $this->get_all_terms_of_post( $cart_item['data']->id );
				
				// If fits the terms, add product in list, and count as a condition filled
				foreach( $conds as $cond_index => $cond ){
					
					if(
						in_array( $cond['cat'], $allTerms )
						&& $cond['cat_number'] > 0
						&& !in_array( $cart_item_key, $this->affected_products )
					){
						
						$numReduced = 0;	// Number of this cart item with reduced price
						
						// Count how much we can reduce
						for($i=0; $i<$cart_item['quantity']; $i++){
							if($cond['cat_number'] > 0){
								$numReduced++;
								$cond['cat_number']--;
								$conds[$cond_index] = $cond;
							}
						}
						
						// Get bundle name
						$bundle_name = WC()->cart->cart_contents[$bundle_cart_key]['data']->post->post_title;
						
						$this->alter_total( $cart_item_key, $numReduced, $cond['max_amount'], $bundle_name );
						
						// Delete condition if all filled
						if($cond['cat_number'] == 0){
							unset( $conds[$cond_index] );
						}
						$this->conditions[$bundle_cart_key] = $conds;
						
						if( empty($this->conditions[$bundle_cart_key]) ){
							unset( $this->conditions[$bundle_cart_key] );
						}
						
						break; // This product can't fill 2 conditions of the same bundle
					}
				}// each conditions
				
			}// each products
		
		}// each bundles
		
		// Update the cart only once, once triggered, remove hook
		remove_action('woocommerce_before_calculate_totals', array( $this, 'get_cart_bundles' ) );
	}// 
	
	
	
	
	/**
	 * Reduce the product price for the number that fits the conditions. And add the rest at full price
	 *
	 * @param $item_key : key reference for the item in the cart
	 * @param $numberReduced : the number of this item that has a reduced price
	 * @param $max_amount : The max price reduction amount applicable to the product
	 * @param $bundle_name : The assigned bundle name thate the item is reduced for.
	 *
	 */
	private function alter_total( $item_key, $numberReduced, $max_amount, $bundle_name ){
		$items = WC()->cart->get_cart();
		$item = $items[$item_key];
		
		// Add to a list that it cant be reduced twice
		$this->affected_products[] = $item_key;
		
		$qty = $item['quantity'];
		$diff = ($qty - $numberReduced);
		
		$item['quantity'] = $numberReduced;
		WC()->cart->set_quantity( $item_key, $numberReduced, false );
		
		// Reduce current product price
		WC()->cart->cart_contents[$item_key]['bundle_before_reduction'] = $item['data']->price;
		$newPrice = ( $item['data']->price - $max_amount );
		$newPrice = max( 0, $newPrice );	// Clamp the lowest price to 0.00$
		$item['data']->price = (string)$newPrice;
		
		// Pass the bundle name as an item data to display later
		WC()->cart->cart_contents[$item_key]['in_bundle'] = $bundle_name;
		
		
		// If there is a quantity of the product that is full price
		if($diff > 0){
			// Remove the calculate totals action to prevent infinite loop
			// Add filter to split items in the cart
			remove_action('woocommerce_before_calculate_totals', array( $this, 'get_cart_bundles' ) );
			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'force_individual_cart_items' ), 10, 3 );
			
			WC()->cart->add_to_cart( $item['product_id'], $diff, $item['variation_id'], $item['variation'] );
			
			remove_filter( 'woocommerce_add_cart_item_data', array( $this, 'force_individual_cart_items' ), 10, 3 );
			add_action('woocommerce_before_calculate_totals', array( $this, 'get_cart_bundles' ) );
		}
		
	}//
	
	
	
	
	/**
	 * For the splitted up items, append '1' string to make it unique and reusable
	 */
	public function force_individual_cart_items( $cart_item_data, $product_id, $variation_id ){
		$unique_cart_item_key = $cart_item_data['unique_key'];
		
		$cart_item_data['unique_key'] = $unique_cart_item_key.'1';
		
		return $cart_item_data;
	}//
	
	
	
	
	/**
	 * Put messages string into the array for further display
	 */
	public function setup_notices(){
		global $post;
		
		// Basic message
		$this->messages[] = __( 'You have a bundle product in your cart.', 'custom_theme' );
		
		// Message for the cart only
		if( 
			is_cart() ||
			is_woocommerce()
		){
			
			// Conditions restantes
			if(!empty( $this->conditions )){
				
				$this->messages[] = __( 'Here\'s the products that you need to add to your cart :', 'custom_theme' );
				
				$allRequired = '<ul>';
				foreach ($this->conditions as $bundle_key => $bundle) {
					foreach ($bundle as $term_id => $conds) {
						$term = get_term( $term_id );
						
						$itemMess = '<li>'.sprintf( __( '%s more from the group "%s" for the bundle "%s" (%s $)', 'custom_theme'),
							$conds['cat_number'],
							'<a href="'.get_term_link($term->term_id).'">'.$term->name.'</a>',
							WC()->cart->cart_contents[$bundle_key]['data']->post->post_title,
							$conds['max_amount']
						).'</li>';
						
						$allRequired .= $itemMess;
					}
				}
				$allRequired .= '</ul>';
				$this->messages[] = $allRequired;
			}else{
				$this->messages[] = __( 'Your bundles are full! Continue to shop or checkout!', 'custom_theme' );
			}
		}
		
		
		// Product page
		if(is_product()){
			
			$terms_ids = $this->get_all_terms_of_post( $post->ID );
			
			// Message if the product fits in a condition
			$fits = false;
			foreach ($this->conditions as $bundle) {
				foreach ($bundle as $term_id => $conds) {
					if( in_array( $term_id, $terms_ids ) ){
						$fits = true;
						break;
					}
				}
			}
			
			if($fits){
				$this->messages[] = __( 'This product fits in your bundle! <strong>Add it now!</strong>', 'custom_theme' );
			}else{
				$this->messages[] = __( 'This product doesn\'t fits in your bundle.', 'custom_theme' );
			}
			
		}
		
	}//
	
	
	
	/**
	 * Remerge the divided product with original pricing before recalculating the conditions and bundles rebates
	 */
	private function reset_cart_double_entries( $cart ){
		$items = $cart->get_cart();
		
		$count = array();
		$uniq = uniqid();
		
		// Count row
		foreach($items as $cart_item_key => $item){
			// Recombine product data
			if(
				$count[$uniq]['product_id'] !== $item['product_id']
				|| $count[$uniq]['variation_id'] != $item['variation_id']
			){
				$uniq = uniqid();
			}
			
			$count[$uniq] = array(
				'product_id'		=> 	$item['product_id'],
				'cart_items_keys'	=>	array_merge( array( $cart_item_key ), (array)$count[$uniq]['cart_items_keys'] ),
				'total_qty'			=>	($count[$uniq]['total_qty'] + $item['quantity']),
				'variation_id'		=>	$item['variation_id'],
				'variation'			=>	$item['variation'],
			);
			
			// Reset price hash
			unset( WC()->cart->cart_contents[$cart_item_key]['in_bundle'] );
			unset( WC()->cart->cart_contents[$cart_item_key]['bundle_before_reduction'] );
		}
		
		
		foreach($count as $uniq => $data){
			
			// If more than one product row
			if( count($data['cart_items_keys']) > 1){
				
				// Remove hook to prevent loop
				remove_action('woocommerce_before_calculate_totals', array( $this, 'get_cart_bundles' ) );
				
				// Removal
				foreach( $data['cart_items_keys'] as $cart_item_key ){
					$cart->remove_cart_item( $cart_item_key );
				}
				
				// Add recombined
				$cart->add_to_cart( $data['product_id'], $data['total_qty'], $data['variation_id'], $data['variation'] );
				add_action('woocommerce_before_calculate_totals', array( $this, 'get_cart_bundles' ) );
				
			}
		}
		
	}//
	
	
	
	
	/**
	 * Adds the bundle name in the item data displayed when listed
	 */
	public function add_item_meta( $item_data, $cart_item ){
		if( $cart_item['in_bundle'] ){
			$item_data['choice_bundle']['name'] = __('Bundle', 'custom_theme');
			$item_data['choice_bundle']['value'] = $cart_item['in_bundle'];
		}
		return $item_data;
	}// 
	
	
	
	
	
	public function display_original_price( $price, $cart_item, $cart_item_key ){
		if( $cart_item['bundle_before_reduction'] && is_cart() ){
			$price = '<del class="before_bundle_reduction">'.wc_price( $cart_item['bundle_before_reduction'] ).'</del><br/>' . $price;
			
		}
		
		return $price;
	}//
	
	
	
	
	
	/**
	 * Echo/Display the notices box and messages in the interface
	 */
	public function display_messages(){
		if( !empty( $this->messages ) ){
			echo '<div class="bundles_notices">';
			foreach( $this->messages as $message ){
				if( substr($message, 0, 1) == '<' ){
					echo $message;
				}else{
					echo '<p>'.$message.'</p>';
				}
			}
			echo '</div>';
		}
	}
	
	
	
	
	/**
	 * Get all the terms of all taxonomies for this post
	 *
	 * @return
	 */
	private function get_all_terms_of_post( $post_id ){
		$post_id = intval( $post_id );
		
		$taxonomy_names = get_post_taxonomies( $post_id );
		$allTerms = array();
		
		foreach($taxonomy_names as $tax){
			$term_list = wp_get_post_terms($post_id, $tax, array("fields" => "ids"));
			$allTerms = array_merge($allTerms, $term_list);
		}
		
		return $allTerms;
	}// 
	
	
	
}// CB_frontend Class

endif;
?>