<?php
/**
 * Theme basic setup.
 *
 * @package understrap
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'theme_enqueue_styles_scripts' ) ) {
	function theme_enqueue_styles_scripts() {
		// Get the theme data.
		$the_theme = wp_get_theme();
		
		/* Styles */
		wp_enqueue_style( 'theme-styles', get_stylesheet_directory_uri() . '/assets/css/theme.min.css', array(), $the_theme->get( 'Version' ) );
		
		/* Scripts */
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'theme-scripts', get_template_directory_uri() . '/assets/js/scripts.min.js', array('jquery'), $the_theme->get( 'Version' ), true );
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
        wp_enqueue_style( 'theme-admin' , get_template_directory_uri().'/assets/css/admin.css' );
        wp_enqueue_script( 'theme-admin' , get_template_directory_uri().'/assets/js/admin.js', array('jquery'));
    }
}
add_action( 'admin_head', 'admin_enqueue_styles_scripts' );

/*
******************************************************************************************************
    Sets up theme defaults and registers support for various WordPress features.
******************************************************************************************************
*/
if ( ! function_exists('theme_setup_features') ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
    function theme_setup_features() {
	    /*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on understrap, use a find and replace
		 * to change 'understrap' to the name of your theme in all the template files
		 */
        load_theme_textdomain( 'understrap', get_template_directory() . '/languages' );
        
        // This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'understrap' ),
		) );
		
		// Check and setup theme default settings.
		understrap_setup_theme_default_settings();
    }
}
add_action( 'after_setup_theme', 'theme_setup_features' );
