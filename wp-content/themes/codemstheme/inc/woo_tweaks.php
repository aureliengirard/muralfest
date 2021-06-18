<?php 
/**
 * Class WOO_Tweaks
 * Description: Classe servant aux modifications de WooCommerce.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 *
 * @category Core
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WOO_Tweaks' ) ) :
	
/**
 * Classe servant aux modifications de WooCommerce.
 *
 * @category Compatibilities
 * @author Codems
 * @version 1.0.0
 */

final class WOO_Tweaks {
	
	/**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;
	
	
	/**
	 * Instance principale de WOO_Tweaks
	 *
	 * Fait en sorte que seulement une instance de la Classe est disponible.
	 *
	 * @return WOO_Tweaks - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Constructeur de la Classe.
	 */
	private function __construct() {
		$this->init_hooks();
		$this->includes();
	}
	
	
	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'remove_hooks' ), 2 );
		
	}
	
	/**
	 * Inclu les fichiers principaux.
	 */
	public function includes() {
		// include_once( 'lib/woo_bundles/woo_choicesBundles.php' );
		// include_once( 'lib/woo_wholesales/woo_wholesales.php' );
	}
	
	
	/**
	 * Retire les hooks de WooCommerce.
	 */
	public function remove_hooks() {
	    // 
	}//
	
	
	
	
	
}// WOO_Tweaks Class
	
/**
 * Retourne l'instance de la Classe.
 * 
 * @see WOO_Tweaks
 */
function WOO_Tweaks() {
	return WOO_Tweaks::instance();
}
WOO_Tweaks(); 

endif;
?>