<?php
/**
 * Class HLP
 * Description: Librairie de fonction pratique pour l'utilisation dans les templates/fonctionnalités.
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

if ( ! class_exists( 'HLP' ) ) :


/**
 * Librairie de fonction pratique pour l'utilisation dans les templates/fonctionnalités.
 *
 * La Class HLP est deprecated et sera supprimer dans une version future.
 * Veuillez utiliser les fonctions globale équivalentes.
 *
 * @deprecated 2.1.0
 * @category Core
 * @author Codems
 * @version 1.0.0
 */
final class HLP {

	/**
	 * Instance static de la Classe.
	 * @var Instance
	 */
	protected static $_instance = null;


	/**
	 * Initialise l'instance de la Classe.
	 *
	 * Fait en sorte que seulement une instance de la Classe est disponible.
	 *
	 * @static
	 * @return HLP - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    /**
     * Redirige tous les appels à la Classe vers les nouvelles fonction globale.
     *
     */
    public function __call($method, $arguments) {
        if(function_exists($method)) {
            trigger_error('La Classe HLP est deprecated, et sera retiré dans une version future. Veuillez utiliser la fonction global équivalente "'.$method.'()" à la place.');
            return call_user_func_array($method, $arguments);
        }
    }



}// HLP Class

endif;

/**
 * Retourne l'instance de la Classe.
 *
 * @deprecated
 * @see HLP
 */
function HLP() {
	return HLP::instance();
}

// Accessible aussi par une global
$GLOBALS['HLP'] = HLP();




if(!function_exists('run_once')){
	/**
	 * S'assure que l'action est exécuté qu'une seule fois.
	 *
	 * @param String $key Nom unique de l'action.
	 * @return bool
	 */
	function run_once( $key ) {
		$test_case = get_option('run_once');
		if (isset($test_case[$key]) && $test_case[$key]){
			return false;
		}else{
			$test_case[$key] = true;
			update_option('run_once',$test_case);
			return true;
		}
	}
}


if(!function_exists('totQueryCount')){
	/**
	 * Affiche le nombre total de résultat de la query.
	 *
	 * @param Class $query WP_Query
	 */
	function totQueryCount( $query ){
		if($query->found_posts){
			echo '<p>'.$query->found_posts.' '.__('results founds', 'custom_theme').'</p>';
		}
	}
}


if(!function_exists('getAllPostsID')){
	/**
	 * Retourne tous les IDs possible d'un custom post type.
	 *
	 * @param String $cpt Nom du custom post type.
	 * @return Array
	 */
	function getAllPostsID( $cpt ){
		if( !post_type_exists($cpt) ){ return array(); }

		$ids = array();

		$args = array(
			'post_type'			=> $cpt,
			'nopaging'			=> true,
		);
		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$ids[] = get_the_ID();
			}
			wp_reset_postdata();
		}
		return $ids;
	}
}


if(!function_exists('truncate')){
	/**
	 * Prend une partie du texte à partir du début et coupe au nombre de caractères donnés.
	 *
	 * @param String $string La chaine à couper.
	 * @param Int $length Le nombre de caractère à garder.
	 * @param String $append Texte à ajouter à la fin.
	 * @param Bool $stripHTML Retirer le HTML de la chaine.
	 * @return String
	 */
	function truncate( $string, $length=100, $append="&hellip;", $stripHTML = false ) {
		$string = trim($string);
		if ( '' != $string ) {
			$string = strip_shortcodes( $string );

			$string = apply_filters('the_content', $string);

			if($stripHTML){
				$string = wp_strip_all_tags($string, true);
			}

			$string = str_replace(']]>', ']]>>', $string);
			$text_length = mb_strlen(strip_tags($string), 'UTF-8'); // Get text length (characters)

			// Shorten the text
			$string = mb_substr($string, 0, $length, 'UTF-8');

			// If the text is more than $length characters, append $excerpt_more
			if ($text_length > $length) {
				$string .= $append;
			}

		}
		return apply_filters('the_excerpt', $string);
	}
}


if(!function_exists('truncate')){
	/**
	 * Traduit une date en français ou vice versa.
	 *
	 *
	 *
	 * @param String $string La chaine à traduire la date.
	 * @param String $to : La langue vers laquel on veut traduire.
	 * @return String
	 */
	function tD($string, $to = 'fr'){
		$m_en = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$m_fr = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

		$s_en = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
		$s_fr = array('Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Août', 'Sep', 'Oct', 'Nov', 'Déc');

		$d_en = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$d_fr = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');

		if($to == 'en'){
			$string = str_ireplace($m_fr, $m_en, $string);
			$string = str_ireplace($d_fr, $d_en, $string);
			$string = str_ireplace($s_fr, $s_en, $string);
		}else{
			$string = str_ireplace($m_en, $m_fr, $string);
			$string = str_ireplace($d_en, $d_fr, $string);
			$string = str_ireplace($s_en, $s_fr, $string);
		}

		return $string;
	}
}