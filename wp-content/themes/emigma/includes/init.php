<?php
	
/*
******************************************************************************************************
    Theme init actions
******************************************************************************************************
*/
if( ! function_exists('theme_init_actions') ){
  add_action('init', 'theme_init_actions');
   function theme_init_actions() {
      add_post_type_support( 'page', 'excerpt' );
  }
}

/*
******************************************************************************************************
    Enqueue them scripts & styles
******************************************************************************************************
*/
if ( ! function_exists('theme_enqueue_styles_scripts') ) {
    add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles_scripts', 100 );
    function theme_enqueue_styles_scripts() {
	    $theme = wp_get_theme();
        $theme_version = $theme->get( 'Version' );
        
        /* Styles */
        wp_enqueue_style( 'main', get_template_directory_uri() . '/style.css', array() );
		wp_enqueue_style( 'theme-child', get_stylesheet_directory_uri() . '/style.css', array('main', 'theme'), $theme_version );
		
		/* Scripts */
		wp_enqueue_script('theme-child', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), $theme_version, true );
    }
}

/*
******************************************************************************************************
    Enqueue admin scripts & styles
******************************************************************************************************
*/
if ( ! function_exists('admin_enqueue_styles_scripts') ) {
    add_action( 'admin_head', 'admin_enqueue_styles_scripts' );
    function admin_enqueue_styles_scripts() {
        wp_enqueue_style( 'theme-admin' , get_stylesheet_directory_uri().'/assets/css/admin.css' );
        wp_enqueue_script( 'theme-admin' , get_stylesheet_directory_uri().'/assets/js/admin.js', array('jquery'), $theme_version, true );
    }
}

/*
******************************************************************************************************
    Sets up theme defaults and registers support for various WordPress features.
******************************************************************************************************
*/
if ( ! function_exists('theme_setup_features') ) {
    add_action( 'after_setup_theme', 'theme_setup_features' );
    function theme_setup_features() {
        $x=load_theme_textdomain( 'emigma', get_stylesheet_directory() . '/languages' );
    }
}

?>