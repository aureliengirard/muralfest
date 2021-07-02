<?php
/**
 * Class Artworks
 * Description: Gère l'envoie des informations vers la map JS.
 * Version: 1.0
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 *
 * @category Addons
 * @author Codems
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Artworks' ) ) :

/**
 * Gère l'envoie des informations vers la map JS.
 *
 * @category Addons
 * @author Codems
 * @version 1.0.0
 */

class Artworks {

	/**
	 * Contient tous les Œuvres du festival.
	 * @var Artworks
	 */
	private $all_artworks = array();

	/**
	 * Constructeur de la Classe.
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
		$this->all_artworks = $this->query_artworks();
	}

	/**
	 * Enregistre les hooks requis.
	 */
	private function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'sendDataJS' ), 30 );
	}


	/**
	 * Inclu les fichiers principaux.
	 */
	public function includes() {

	}


	/**
	 * Envoie des variables vers certains scripts.
	 *
	 */
	public function sendDataJS(){
		// Localize the script with new data
		wp_localize_script( 'artworks-map', 'artworks', $this->get_artworks() );

		wp_localize_script( 'artworks-map', 'translation_map', array(
			'readmore' => __('Learn more +', 'site-theme'),
			'by' => __('By:', 'site-theme')
		));
    }


    /**
     * Retourne toutes les œuvres pour un festival
     *
     * @param String $festival
     * @return Array|Bool
     */
    private function query_artworks(){

        $artworks = array();

        $args = array(
            'post_type' => array( 'artwork' ),
			'posts_per_page' => '-1',
			'orderby' => array(
				'year' => 'DESC',
				'title' => 'ASC'
			),
			'meta_query' => array(
				'year' => array(
					'key' => 'annee'
				)
			)
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
				$query->the_post();

				ob_start();
				get_template_part('parts/inc', 'share');
				$share = ob_get_clean();

                $artworks[get_the_ID()] = array(
                    'title' => get_the_title(),
                    'thumbnail' => wp_get_attachment_image( get_field('image_de_loeuvre'), 'artwork-thumb' ),
					'description' => truncate(get_field('resume'), 140),
					'link' => get_the_permalink(),
                    'artist' => array(
						'name' => get_the_title(get_field('artiste')),
						'link' => get_the_permalink(get_field('artiste'))
					),
                    'date' => get_field('annee'),
					'coords' => get_field('lieu_de_loeuvre'),
					'share' => $share
                );
            }
        }

        wp_reset_postdata();

        return $artworks;
	}


	/**
	 * Retourne tous les Œuvres
	 */
	 public function get_artworks(){
		 return $this->all_artworks;
	 }

}// Artworks Class

endif;