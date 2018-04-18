<div class="custom-bg bgcolor-<?= get_sub_field('couleur'); ?>">
    
    <?php if ( have_rows( 'sub_contenu' ) ) : ?>
        <?php while ( have_rows('sub_contenu' ) ) { the_row();
            /**
             * @hooked add_default_part_classes()
             */
            $sectionClasses = apply_filters('cdm_add_section_classes', '');
            echo '<section class="'. trim($sectionClasses) .'">';
                get_template_part( 'parts/part', get_row_layout() );
            echo '</section>';
        } ?>
    <?php endif; ?>

    <?php if(get_sub_field('ajouter_une_image')){
        $img_attr = array();

        if(get_sub_field('ajouter_du_parallax')){
            $img_attr['class'] = 'img-parallax';
        }else{
            $img_attr['class'] = 'background-image';
        }

        echo wp_get_attachment_image(get_sub_field('image_de_fond'), 'full', false, $img_attr);
    } ?>
</div>