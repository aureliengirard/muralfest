<?php 
/**
 * Class SectionsLoader
 * Description: Gestionnaire du système de sections par parts.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.8.2
 *
 *
 * @category Core
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SectionsLoader' ) ) :
	
/**
 * Gestionnaire du système de sections par parts.
 *
 * @category Core
 * @author Codems
 * @version 1.0.0
 */

final class SectionsLoader {
	
	/**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;
	
	/**
	 * Boolean qui vérifie que seulement une part est affiché.
	 * 
	 * @var Part_already_loaded
	 */
	private $part_already_loaded = false;
	
	
	/**
	 * Instance pricipale SectionsLoader
	 *
	 * Fait en sorte que seulement une instance de la Classe est disponible.
	 *
	 * @return SectionsLoader - Main instance
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
	}
	
	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
		add_filter( 'cdm_add_section_classes', array( $this, 'add_default_part_classes' ), 1, 1);
		
		add_action( 'get_template_part_parts/part', array( $this, 'prevent_double_loading_part' ), 10 );
	}
	
	
	/**
	 * Ajoute les classes par défaut au section original
	 * pour rester compatible avec les vieux site.
	 * 
	 * @param $classes (String) Classes actuelles
	 * @hooked cdm_add_section_classes
	 * @return string
	 */
	public function add_default_part_classes($classes){
		if ( get_row_layout() == 'titre_section' ){
			$classes .= ' titre-section';
			
		}elseif( get_row_layout() == 'texte_pleine_largeur' ){
			$classes .= ' texte-plein';
			
		}elseif( get_row_layout() == 'image_et_texte' ){
			$classes .= ' image-texte';
			
		}elseif( get_row_layout() == 'bouton' ){
			$classes .= ' zone-bouton';
			
		}elseif( get_row_layout() == 'faq' ){
			$classes .= ' faq';
			
		}elseif( get_row_layout() == 'map' ){
			$classes .= ' section-map';
			
		}elseif( get_row_layout() == 'galerie_image' ){
			$classes .= ' galerie';
			
		}elseif( get_row_layout() == 'formulaire_et_informations' ){
			$classes .= ' contact-form';
			
		}else{ // default
			$classes .= ' '.get_row_layout();
		}
		
		return $classes;
	}
	
	
	/**
	 * Change le boolean après avoir affichée une part.
	 * 
	 * @see SectionsLoader::part_already_loaded
	 * @hooked get_template_part_parts/part
	 */
	public function prevent_double_loading_part(){
		$this->part_already_loaded = true;
	}
	
	
	
	/**
	 * Réinitialise le boolean de sécurité.
	 *
	 * @see SectionsLoader::part_already_loaded
	 */
	public function reset_loader_security(){
		$this->part_already_loaded = false;
	}
	
	
	/**
	 * Vérifie si une part est déjà affichée.
	 * 
	 * @return Bool
	 */
	public function isAlreadyLoaded(){
		return $this->part_already_loaded;
	}
	
	
	
}// SectionsLoader Class
	
/**
 * Retourne l'instance de la Classe.
 * 
 * @see SectionsLoader
 */
function SectionsLoader() {
	return SectionsLoader::instance();
}
SectionsLoader();

endif;
?>