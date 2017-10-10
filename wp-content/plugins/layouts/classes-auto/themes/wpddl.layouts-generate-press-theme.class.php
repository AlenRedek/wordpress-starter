<?php

/**
 * Class Toolset_Compatibility_Theme_generatepress
 * @since layouts 2.0.2
 */
class Toolset_Compatibility_Theme_generatepress extends Toolset_Compatibility_Theme_Handler{


	protected function run_hooks() {
		add_action( 'get_header', array( $this, 'disable_featured_image' ) );
		add_action( 'get_header', array( $this, 'disable_pagination' ) );
		add_action( 'get_header', array( $this, 'disable_title' ) );
		add_action( 'get_header', array( $this, 'disable_archive_title') );

		add_filter( 'toolset_add_registered_styles', array( $this, 'add_register_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
	}

	public function disable_archive_title(){
		$toolset_disable_archive_title = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_title' );

		if ( "1" == $toolset_disable_archive_title && is_archive() ) {
			remove_action( 'generate_archive_title','generate_archive_title' );
		}
	}

	/**
	 * Get value from theme integration settings filter and disable featured image for current page if option is enabled
	 */
	public function disable_featured_image() {
		$toolset_disable_featured_image = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_featured_image' );

		if ( "1" == $toolset_disable_featured_image ) {
			if( is_single() ){
				remove_action( 'generate_before_content','generate_featured_page_header_inside_single', 10 );
			} elseif( is_page() ){
				remove_action( 'generate_after_header', 'generate_featured_page_header', 10 );
			}
		}
	}
	/**
	 * Get value from theme integration settings filter and disable pagination image for current page if option is enabled
	 * We are doing this with small js code, since theme doesn't provide any hook to do it
	 */
	public function disable_pagination() {
		$toolset_disable_pagination = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_pagination' );

		if ( "1" == $toolset_disable_pagination ) {
			add_action( 'wp_head', array( $this, 'remove_pagination_box' ) );
		}
	}

	public function remove_pagination_box( ) {
		echo '<script type="text/javascript">jQuery(function() { jQuery(".paging-navigation").remove(); });</script>';
	}

	/**
	 * Get value from theme integration settings filter and disable title for current page if option is enabled
	 */
	public function disable_title() {
		$toolset_disable_title = apply_filters( 'toolset_theme_integration_get_setting', null, 'toolset_disable_title' );

		if ( "1" == $toolset_disable_title ) {
			add_filter( 'the_title', array( $this, 'remove_title' ), 10, 2 );
			return true;
		}
		return false;
	}

	public function remove_title( $title, $id ) {

		if ( in_the_loop() ) {
			remove_filter( 'the_title', array( $this, 'remove_title' ), 10, 2 );
			return '';
		}

		return $title;
	}

	public function add_register_styles( $styles ) {
		$styles['generatepress-overrides-css'] = new WPDDL_style( 'generatepress-overrides-css', WPDDL_RES_RELPATH . '/css/themes/generatepress-overrides.css', array(), WPDDL_VERSION, 'screen' );
		return $styles;
	}

	public function frontend_enqueue() {
		do_action( 'toolset_enqueue_styles', array( 'generatepress-overrides-css' ) );
	}




}