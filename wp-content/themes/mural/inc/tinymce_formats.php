<?php

/* extend tiny_mce */
add_action( 'after_setup_theme', 'extend_editor_after_setup_theme' );
add_filter('mce_buttons_2', 'extend_editor_mce_buttons_2' );
add_filter('tiny_mce_before_init', 'extend_editor_tiny_mce_before_init' );

/**
 * Extension de l'éditeur texte de Wordpress pour avoir des formats custom
 */
function extend_editor_after_setup_theme(){
    add_editor_style();
}

function extend_editor_mce_buttons_2($buttons){
    array_unshift($buttons, 'styleselect');
    return $buttons;
}

function extend_editor_tiny_mce_before_init($settings){
    
    $settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';

    // From http://tinymce.moxiecode.com/examples/example_24.php
    $style_formats = array(         
        array('title' => 'Bouton', 'selector' => 'a', 'classes' => 'button'),
    );
    
    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;
}