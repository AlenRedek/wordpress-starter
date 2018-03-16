<?php
/**
 * Understrap enqueue scripts
 *
 * @package understrap
 */

if ( ! function_exists( 'theme_enqueue_styles_scripts' ) ) {
	function theme_enqueue_styles_scripts() {
		// Get the theme data.
		$the_theme = wp_get_theme();
		
		/* Styles */
		wp_enqueue_style( 'theme-styles', get_stylesheet_directory_uri() . '/assets/css/theme.min.css', array(), $the_theme->get( 'Version' ) );
		
		/* Scripts */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'theme-scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', array('jquery'), $the_theme->get( 'Version' ), true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		wp_enqueue_script( 'theme-custom', get_template_directory_uri() . '/assets/js/custom.js', array('theme-scripts'), $the_theme->get( 'Version' ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles_scripts' );

/*
******************************************************************************************************
    Enqueue admin scripts & styles
******************************************************************************************************
*/
if ( ! function_exists('admin_enqueue_styles_scripts') ) {
    function admin_enqueue_styles_scripts() {
        wp_enqueue_style( 'theme-admin' , get_stylesheet_directory_uri().'/assets/css/admin.css' );
        wp_enqueue_script( 'theme-admin' , get_stylesheet_directory_uri().'/assets/js/admin.js', array('jquery'));
    }
}
add_action( 'admin_head', 'admin_enqueue_styles_scripts' );