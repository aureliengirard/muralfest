<?php // Start Flexible Content
if ( have_rows( 'contenu' ) ) {
	 while ( have_rows('contenu' ) ) { the_row();
		
		// Set the loaded to false when begining a new iteration
		SectionsLoader()->reset_loader_security();
		
		/**
		 * @hooked add_default_part_classes()
		 */
		$sectionClasses = apply_filters('cdm_add_section_classes', '');

		do_action( 'cdm_before_part_render', $sectionClasses );
		
		/**
		 * this hook has been replaced
		 *
		 * @deprecated This action has been deprecated and replaced by cdm_after_part_render
		 */
		do_action( 'add_content_section', $sectionClasses );
		
		// the if make sure we dont double load a section for site using the manual include
		if(!SectionsLoader()->isAlreadyLoaded()){
			echo '<section class="'. trim($sectionClasses) .'">';
				get_template_part( 'parts/part', get_row_layout() );
			echo '</section>';
		}
		
		do_action( 'cdm_after_part_render', $sectionClasses );
	}
}