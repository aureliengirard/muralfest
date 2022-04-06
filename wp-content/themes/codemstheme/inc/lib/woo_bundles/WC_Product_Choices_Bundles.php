<?php 
/**
 * Class WC_Product_Choices_Bundle
 * Description: New product type class for the Choices Bundles module
 * Version: 1.0
 * Author: Isaac Doré
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.6
 *
 *
 * @category Woocommerce
 * @author Codems
**/


class WC_Product_Choices_Bundle extends WC_Product {
	public function __construct( $product ) {

		$this->product_type = 'choices_bundle';
		$this->supports[]   = 'ajax_add_to_cart';
		
		parent::__construct( $product );

	}
	
	
	/**
	 * Return the current post bundle conditions saved values if good type
	 *
	 * @return multidimensionnal array with values
	 */
	public function get_bundle_conditions(){
		$results = array();
		
		$metas = get_post_meta( $this->id );
		foreach ($metas as $key => $value) {
			if( substr($key, 0, 8) == '_bundle_' ){
				
				$parts = explode( '_', $key );
				
				$index = end($parts); // Row index
				
				$newKey = str_replace( '_'.$index, '', $key);
				$newKey = str_replace( '_bundle_', '', $newKey);
				
				$results[$index][$newKey] = get_post_meta( $this->id, $key, true ); // Re-Retreive the value to avoid array nested values
			}
		}
		
		return $results;
	}//
	
	
	
	
	
	
	/*****************************************************
		Copy of the product Simple Functions to get some behaviors
	/*****************************************************/
	
	
	/**
	 * Get the add to url used mainly in loops.
	 *
	 * @return string
	 */
	public function add_to_cart_url() {
		$url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );

		return apply_filters( 'woocommerce_product_add_to_cart_url', $url, $this );
	}

	/**
	 * Get the add to cart button text.
	 *
	 * @return string
	 */
	public function add_to_cart_text() {
		$text = $this->is_purchasable() && $this->is_in_stock() ? __( 'Add to cart', 'woocommerce' ) : __( 'Read more', 'woocommerce' );

		return apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );
	}

	/**
	 * Get the title of the post.
	 *
	 * @return string
	 */
	public function get_title() {
		$title = $this->post->post_title;

		if ( $this->get_parent() > 0 ) {
			$title = get_the_title( $this->get_parent() ) . ' &rarr; ' . $title;
		}
		return apply_filters( 'woocommerce_product_title', $title, $this );
	}
	
	
	
	
	
}
?>