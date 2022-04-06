<?php 
/**
 * Class WOO_ChoicesBundles
 * Description: Manage the Choices Bundle Main display in the back-end and trigger all the others logics
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

if ( ! class_exists( 'WOO_ChoicesBundles' ) ) :
	
final class WOO_ChoicesBundles {
	
	// An array containing taxonomy's name that can be selected for the bundles in the backend
	private $allowed_taxonomies = array( 'product_cat', 'collections' );
	
	
	
	// Static instance of the class
	protected static $_instance = null;
	
	// Front-end management class
	protected $_front = null;
	
	
	/**
	 * Main WOO_ChoicesBundles Instance
	 *
	 * Ensures only one instance of WOO_ChoicesBundles is loaded or can be loaded.
	 *
	 * @static
	 * @return WOO_ChoicesBundles - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * WOO_ChoicesBundles Constructor (called on wp init).
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}
	
	
	/**
	 * WOO_ChoicesBundles necessary modules
	 */
	private function includes(){
		include_once( 'class-cb-frontend.php' );
		
		$this->_front = CB_frontend::instance();
	}//
	
	
	/**
	 * Register necesary hooks
	 */
	private function init_hooks() {
		add_action( 'init', array($this, 'register_choices_bundle_product_type') );
		add_action( 'woocommerce_choices_bundle_add_to_cart', array($this, 'choices_bundle_add_to_cart'), 30 );
		
		add_filter( 'product_type_selector', array($this, 'add_choices_bundle_product') );
		
		add_filter( 'woocommerce_product_data_tabs', array($this, 'hide_other_data_panel'), 20, 1 );
		add_filter( 'woocommerce_product_data_tabs', array($this, 'custom_product_tabs'), 22, 1 );
		add_action( 'admin_footer', array($this, 'choices_bundle_custom_js') );
		
		// Options 
		add_action( 'woocommerce_product_data_panels', array($this, 'bundle_options_product_tab_content') );
		
		// Options save
		add_action( 'woocommerce_process_product_meta_choices_bundle', array($this, 'save_bundle_options_fields')  );
		
		// Add JS and style
		add_action( 'admin_enqueue_scripts', array($this, 'add_scripts') );
		
	}//
	
	
	/**
	 * Register the custom product type after init
	 */
	public function register_choices_bundle_product_type() {
		include_once( 'WC_Product_Choices_Bundles.php' );
	}
	
	
	/**
	 * Enqueue necessary scripts for the backend dynamism of the feature
	 */
	public function add_scripts(){
		// Admin stuff
		wp_enqueue_style( 'choices_bundle_css', THEMEURI.'/inc/lib/woo_bundles/assets/style.css', false, '1.0.0' );
		wp_enqueue_script( 'choices_bundle_admin_script', THEMEURI.'/inc/lib/woo_bundles/assets/backend.js' );
	}
	
	
	/**
	 * Add the add to cart properly for woocommerce.
	 */
	public function choices_bundle_add_to_cart(){
		wc_get_template( 'single-product/add-to-cart/simple.php' );
	}
	
	
	

	/**
	 * Add the product type in the select choices in woocommerce
	 */
	public function add_choices_bundle_product( $types ){
		// Key should be exactly the same as in the class product_type parameter
		$types[ 'choices_bundle' ] = __( 'Bundle / Kit', 'custom_theme' );
		
		return $types;
	}
	
	
	/**
	 * Add a custom product tab.
	 */
	public function custom_product_tabs( $tabs ) {
		$tabs['choices_bundle'] = array(
			'label'		=> __( 'Options Bundle', 'custom_theme' ),
			'target'	=> 'choices_bundle_options',
			'class'		=> array( 'show_if_choices_bundle' ),
		);
		return $tabs;
	}
	
	
	
	/**
	 * Hides some tabs when this product type is selected
	 */
	public function hide_other_data_panel( $tabs ){
		$tabs['attribute']['class'][] = 'hide_if_choices_bundle';
		$tabs['shipping']['class'][] = 'hide_if_choices_bundle';
		$tabs['linked_product']['class'][] = 'hide_if_choices_bundle';
		$tabs['variations']['class'][] = 'hide_if_choices_bundle';
		$tabs['advanced']['class'][] = 'hide_if_choices_bundle';

		return $tabs;
	}
	
	
	
	
	/**
	 * Show pricing fields for choices_bundle product.
	 */
	public function choices_bundle_custom_js() {
		if ( 'product' != get_post_type() ) :
			return;
		endif;

		echo "<script type='text/javascript'>
			jQuery( document ).ready( function() {
				jQuery( '.general_options.general_tab' ).show();
				jQuery( '.options_group.pricing' ).addClass( 'show_if_choices_bundle' ).show();
				jQuery( '#general_product_data > div.options_group:last-child' ).addClass( 'show_if_choices_bundle' ).show();
			});
		</script>";
	}
	
	
	
	/**
	 * Display the options and structure for the bundle options tab content
	 */
	public function bundle_options_product_tab_content() {
		global $post;
		
		$product = wc_get_product( $post->ID );
		
		if($product->product_type == 'choices_bundle'){
			$saved = $product->get_bundle_conditions();
		}
		
		// If nothing saved yet, default row
		if(empty($saved)){
			$saved['1'] = array( );
		}
		
		echo "<div id='choices_bundle_options' class='panel woocommerce_options_panel'>";
			echo "<div class='options_group'>";
				
				
				foreach($saved as $rowIndex => $data){
					echo '<div class="bundle_choice options_group">';
						
						if($rowIndex !== 1){
							echo '<a href="#" class="remove_bundle_row button">X</a>';
						}
						
						$select_args = array(
							'id'      => '_bundle_cat_'.$rowIndex,
							'label'   => __( 'Category', 'custom_theme' ),
							'options' => array(),
							'value'	  => $data['cat'],
						);
						
						// Add the taxonomies terms
						foreach( $this->allowed_taxonomies as $tax ){
							$terms = get_terms( array(
								'taxonomy' => $tax,
							) );
							foreach($terms as $term){
								$select_args['options'][$term->term_id] = $term->name;
							}
						}
						
						woocommerce_wp_select( $select_args );
						
						echo '<p class="form-field _bundle_cat_number_'. $rowIndex .'_field">';
							echo '<label for="_bundle_cat_number_'. $rowIndex .'">'. __( 'Number included', 'custom_theme' ) .'</label>';
							echo '<input style="" name="_bundle_cat_number_'. $rowIndex .'" id="_bundle_cat_number_'. $rowIndex .'" value="'. ($data['cat_number']?$data['cat_number']:1) .'" placeholder="" type="number" min="0" max="999">';
						echo '</p>';
						
						echo '<p class="form-field _bundle_max_amount_'. $rowIndex .'">';
							echo '<label for="_bundle_max_amount_'. $rowIndex .'">'. __( 'Max amount reduced by product', 'custom_theme' ) .'</label>';
							echo '<input class="short wc_input_price" style="" name="_bundle_max_amount_'. $rowIndex .'" id="_max_amount" value="'. ($data['max_amount']?$data['max_amount']:10) .'" placeholder="" type="text">';
						echo '</p>';
						
					echo '</div>';
				}
				
				echo '<div id="bundle_options_actions">';
					echo '<a href="#" id="add_bundle_choice" class="button">'. __( 'Add a product group', 'custom_theme' ) .'</a>';
				echo '</div>';
			echo '</div>';

		echo '</div>';
	}//
	



	/**
	 * Save the custom fields.
	 */
	public function save_bundle_options_fields( $post_id ) {
		$factory = new WC_Product_Factory();
		$bundle = $factory->get_product( $post_id );
		
		$saved = $bundle->get_bundle_conditions();
		
		foreach($_POST as $key => $value){
			if( substr($key, 0, 8) == '_bundle_' ){
				update_post_meta( $post_id, $key, sanitize_text_field( $_POST[$key] ) );
			}
		}
		
		// Unset old values
		foreach($saved as $index => $data){
			foreach($data as $key => $value){
				if( !isset($_POST['_bundle_'.$key.'_'.$index]) ){
					delete_post_meta($post_id, '_bundle_'.$key.'_'.$index);
				}
			}
		}
		
	}//
	
	
	
	
	
}// WOO_ChoicesBundles Class
	
function WOO_ChoicesBundles() {
	return WOO_ChoicesBundles::instance();
}
WOO_ChoicesBundles(); 

endif;
?>