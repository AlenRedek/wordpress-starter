<?php

/**
 * Compatibility class for Ocean WP theme
 * Class Toolset_Compatibility_Theme_oceanwp
 */
class Toolset_Compatibility_Theme_astra extends Toolset_Compatibility_Theme_Handler {

    public function add_register_styles( $styles ) {

        $styles['astra-overrides-css'] = new WPDDL_style( 'astra-overrides-css', WPDDL_RES_RELPATH . '/css/themes/astra-overrides.css', array(), WPDDL_VERSION, 'screen' );

        return $styles;
    }

    public function frontend_enqueue() {
        do_action( 'toolset_enqueue_styles', array( 'astra-overrides-css' ) );
    }

    protected function run_hooks() {
        add_filter( 'toolset_add_registered_styles', array( &$this, 'add_register_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
	    add_action( 'get_header', array( $this, 'disable_featured_image' ) );
	    add_action( 'get_header', array( $this, 'disable_title' ) );
	    add_action( 'get_header', array( $this, 'disable_pagination' ) );
	    add_action( 'get_header', array( $this, 'disable_read_more_for_excerpt' ) );
    }

    public function disable_pagination(){
	    $toolset_disable_pagination = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_pagination' );
	    if ( "1" === $toolset_disable_pagination ) {
		    remove_filter( 'astra_pagination', 'astra_number_pagination' );
	    }
    }

	public function disable_read_more_for_excerpt(){
		$toolset_disable_read_more = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_read_more' );
		if ( "1" === $toolset_disable_read_more ) {
			remove_filter( 'excerpt_more', 'astra_post_link', 1 );
		}
	}

	/**
	 * Get value from theme integration settings filter and disable featured image for current page if option is enabled
	 */
	public function disable_featured_image() {

		$toolset_disable_featured_image = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_featured_image' );

		if ( "1" === $toolset_disable_featured_image ) {
			add_filter( 'astra_featured_image_enabled', '__return_false' );
		}
	}

	/**
	 * Get value from theme integration settings filter and disable title for current page if option is enabled
	 */
	public function disable_title() {
		$toolset_disable_title = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_title' );

		if ( "1" == $toolset_disable_title ) {
			add_filter( 'astra_the_title_enabled', '__return_false' );
		}
	}
}