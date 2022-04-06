<?php
/**
 * Very similar to the general inc-content, but simplyfied.
 * This make possible to have dynamic content in the columns
 * with the same general style for all sections.
 * 
 */
?>

<div class="col-wrapper<?= (get_sub_field('largeur_complete') ? ' full-width' : '') ?>">
    <?php if ( have_rows( 'colonne_de_gauche' ) ) : ?>
        <div class="left-col">
            <?php while ( have_rows('colonne_de_gauche' ) ) { the_row();
                $classes = 'bgcolor-'.get_sub_field('couleur');
                if(get_sub_field('ajouter_une_image') && get_sub_field('ajouter_du_parallax')){
                    $classes .= ' background-parallax';
                }

                if(get_sub_field('content_width')){
                    $classes .= ' content-width';
                }
                
                echo '<section class="'.$classes.'">';

                if ( have_rows( 'contenu_colonne' ) ){
                    echo '<div class="col-content">';
                        while ( have_rows('contenu_colonne' ) ) { the_row();
                            /**
                             * @hooked add_default_part_classes()
                             */
                            $sectionClasses = apply_filters('cdm_add_section_classes', '');

                            echo '<section class="'. trim($sectionClasses) .'">';
                                get_template_part( 'parts/part', get_row_layout() );
                            echo '</section>';
                        }
                    echo '</div>';
                }

                if(get_sub_field('ajouter_une_image')){
                    $img_attr = array();
            
                    if(get_sub_field('ajouter_du_parallax')){
                        $img_attr['class'] = 'img-parallax';
                    }else{
                        $img_attr['class'] = 'background-image';
                    }
            
                    echo wp_get_attachment_image(get_sub_field('image_de_fond'), 'full', false, $img_attr);
                }
        
                echo '</section>';
            } ?>
        </div>
    <?php endif; ?>

    <?php if ( have_rows( 'colonne_de_droite' ) ) : ?>
        <div class="right-col">
            <?php while ( have_rows('colonne_de_droite' ) ) { the_row();
                $classes = 'bgcolor-'.get_sub_field('couleur');
                if(get_sub_field('ajouter_une_image') && get_sub_field('ajouter_du_parallax')){
                    $classes .= ' background-parallax';
                }

                if(get_sub_field('content_width')){
                    $classes .= ' content-width';
                }
                
                echo '<section class="'.$classes.'">';

                if ( have_rows( 'contenu_colonne' ) ){
                    echo '<div class="col-content">';
                        while ( have_rows('contenu_colonne' ) ) { the_row();
                            /**
                             * @hooked add_default_part_classes()
                             */
                            $sectionClasses = apply_filters('cdm_add_section_classes', '');

                            echo '<section class="'. trim($sectionClasses) .'">';
                                get_template_part( 'parts/part', get_row_layout() );
                            echo '</section>';
                        }
                    echo '</div>';
                }

                if(get_sub_field('ajouter_une_image')){
                    $img_attr = array();
            
                    if(get_sub_field('ajouter_du_parallax')){
                        $img_attr['class'] = 'img-parallax';
                    }else{
                        $img_attr['class'] = 'background-image';
                    }
            
                    echo wp_get_attachment_image(get_sub_field('image_de_fond'), 'full', false, $img_attr);
                }
        
                echo '</section>';
            } ?>
        </div>
    <?php endif; ?>
</div>