<?php
/*
Plugin Name: Gravity Forms Multilingual
Plugin URI: http://wpml.org/documentation/related-projects/gravity-forms-multilingual/
Description: Add multilingual support for Gravity Forms
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com/
Version: 1.3-RC2
*/

if ( defined ( 'GRAVITYFORMS_MULTILINGUAL_VERSION' ) ) {
    return;
}

add_action ( 'init', 'load_gfml' );

function load_gfml() {
    if ( defined ( 'ICL_SITEPRESS_VERSION' ) ) {
        define( 'GRAVITYFORMS_MULTILINGUAL_VERSION', '1.3' );
        define( 'GRAVITYFORMS_MULTILINGUAL_PATH', dirname ( __FILE__ ) );

        require GRAVITYFORMS_MULTILINGUAL_PATH . '/inc/gravity-forms-multilingual.class.php';

        if ( version_compare ( ICL_SITEPRESS_VERSION, '3.2', '<' ) ) {
            require GRAVITYFORMS_MULTILINGUAL_PATH . '/inc/gfml-tm-legacy-api.class.php';
            new GFML_TM_Legacy_API();
        } else {
            require GRAVITYFORMS_MULTILINGUAL_PATH . '/inc/gfml-tm-api.class.php';
            new GFML_TM_API();
        }
    }
}