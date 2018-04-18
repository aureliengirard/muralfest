<?php
/**
 * Very similar to the general inc-content, but simplyfied.
 * This make possible to have dynamic content in the columns
 * with the same general style for all sections.
 * 
 */
?>

<div class="col-wrapper">
    <?php if ( have_rows( 'colonne_de_gauche' ) ) : ?>
        <div class="left-col">
            <?php while ( have_rows('colonne_de_gauche' ) ) { the_row();
                /**
                 * @hooked add_default_part_classes()
                 */
                $sectionClasses = apply_filters('cdm_add_section_classes', '');

                echo '<section class="'. trim($sectionClasses) .'">';
                    get_template_part( 'parts/part', get_row_layout() );
                echo '</section>';
            } ?>
        </div>
    <?php endif; ?>

    <?php if ( have_rows( 'colonne_de_droite' ) ) : ?>
        <div class="right-col">
            <?php while ( have_rows('colonne_de_droite' ) ) { the_row();
                /**
                 * @hooked add_default_part_classes()
                 */
                $sectionClasses = apply_filters('cdm_add_section_classes', '');

                echo '<section class="'. trim($sectionClasses) .'">';
                    get_template_part( 'parts/part', get_row_layout() );
                echo '</section>';
            } ?>
        </div>
    <?php endif; ?>
</div>