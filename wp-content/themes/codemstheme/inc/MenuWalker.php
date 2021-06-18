<?php 
/**
 * Class MenuWalker
 * Description: Le menu Walker principale, il permet d'avoir une hiérachie entre les pages et les CPT.
 * Version: 1.2
 * Author: Codems
 * Author URI: http://codems.ca
 * Requires at least: 4.0
 * Tested up to: 4.8.3
 *
 *
 * @category Core
 * @author Codems
**/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'MenuWalker' ) ) :
	
/**
 * Le menu Walker principale, il permet d'avoir une hiérachie entre les pages et les CPT.
 *
 * @category Utilities
 * @author Codems
 * @version 1.0.0
 */	

class MenuWalker extends Walker_Nav_Menu {
	
	/**
	 * Indique au Walker les paramètres à hériter de son parent.
	 * @var Db_fields
	 */
	var $db_fields = array(
		'parent' => 'menu_item_parent', 
		'id'     => 'db_id' 
	);
	
	
	/**
	 * Permet de modifier se que chaque élément affiche à son début.
	 * 
	 * @param String $output HTML qui va être affiché.
	 * @param Object $item Object de l'élément actuelle.
	 * @param Int $depth
	 * @param Array $args
	 * @param Int $id ID de l'élément actuel.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $post_id = $item->object_id; // current menu item id
		$page_id = get_the_ID(); // current page id
		
		do_action('cdm_menu_customize', $output, $item, $depth, $args, $id);
		
		if ( have_rows( 'liaison_cpt_pages', 'options' ) ) :
			while ( have_rows( 'liaison_cpt_pages', 'options' ) ) : the_row();
				if($item->type == 'wpml_ls_menu_item'){ // pour éviter que les switchers de langue soit considéré comme le parent de tous les custom post type ayant une liaison
					continue;
				}
				$child_pages = get_pages(['child_of' => $post_id]); // get child page of current page
				$ids = array(); // array of all the ids of the current page childrens

				if(!empty($child_pages)){
					foreach($child_pages as $child) {
							$ids[] = $child->ID;
					}   
				}

				$ids = apply_filters( 'cdm_menu_walker_child_ids', $ids );

				if($post_id == get_sub_field( 'page' ) || in_array(get_sub_field( 'page' ), $ids)){
					if(get_sub_field('afficher_menu')){
						if(array_search('menu-item-has-children', $item->classes) === false){
							$item->classes[] = 'menu-item-has-children';
						}
					}
					
					if(get_post_type($page_id) == get_sub_field( 'article_personnalise' )){

						$item->current_item_ancestor = true;
						$item->current_item_parent = true;
						$item->classes = array_merge($item->classes, array(
							'current-page-ancestor',
							'current-menu-ancestor',
							'current-menu-parent',
							'current-page-parent',
							'current_page_parent',
							'current_page_ancestor'
						));
					}
				}
			endwhile;
		endif;

		if($args->depth > 0){ // si une depth à été défini dans les args
			if(($depth + 1) >= $args->depth){ // si le prochain niveau n'est pas afficher
				$pos = array_search('menu-item-has-children', $item->classes);
				unset($item->classes[$pos]);
			}
		}
		
		$classes = (!empty($item->classes)? ' class="'.implode(' ', $item->classes).'"':'');
		
		$output .= '<li'.$classes.'><a href="'.$item->url.'" target="'.$item->target.'">'.$item->title.'</a>';
    }
	
	
	/**
	 * Permet de modifier se que chaque élément affiche à sa fin.
	 * 
	 * @param String $output HTML qui va être affiché.
	 * @param Object $item Object de l'élément actuelle.
	 * @param Int $depth
	 * @param Array $args
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$post_id = $item->object_id;
		$page_id = get_the_ID();
		$custom_posts_menu = array();
		$parent_id = ($depth > 0 ? wp_get_post_parent_id($post_id) : 0);
		
		// liaisons
		if ( have_rows( 'liaison_cpt_pages', 'options' ) ) :
			while ( have_rows( 'liaison_cpt_pages', 'options' ) ) : the_row();
				if(!get_sub_field('afficher_menu'))
					continue;
		
				if($post_id == get_sub_field( 'page' )){
					$args = array(
						'post_type' => array( get_sub_field( 'article_personnalise' ) ),
						'numberposts' => '-1',
						'orderby' => 'menu_order title',
						'order' => 'ASC',
                        'suppress_filters' => false,
					);
					
					$args = apply_filters( 'cdm_menu_walker_sub_page_args', $args );
					
					$custom_posts_menu = get_posts($args); // get sub posts
					
					break;
				}
			endwhile;
			
			$custom_posts_menu = array_map( 'wp_setup_nav_menu_item', $custom_posts_menu );
			
			if(!empty($custom_posts_menu)){
				
				// Update some value that WP does not set because our post is not in the menu
				foreach ($custom_posts_menu as $key => $elem) {
					$elem->db_id = $elem->ID;
					$elem->menu_item_parent = wp_get_post_parent_id($elem->ID);
					$elem->object = 'page'; // set it to page so we have the same class for all menu items
					
					$custom_posts_menu[$key] = $elem;
				}
				
				_wp_menu_item_classes_by_context( $custom_posts_menu ); // apply classes
				
				$this->start_lvl( $output );
				$output .= $this->walk($custom_posts_menu, 0);
				$this->end_lvl( $output );
			}
			
		endif;		
	
		
		$output .= "</li>\n";
	}
	
	
}// MenuWalker Class

endif;

?>