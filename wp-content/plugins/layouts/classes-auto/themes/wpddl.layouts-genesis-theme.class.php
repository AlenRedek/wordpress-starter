<?php
/**
 * Compatibility class for Genesis theme
 * Class Toolset_Compatibility_Theme_genesis
 */
class Toolset_Compatibility_Theme_genesis extends Toolset_Compatibility_Theme_Handler {


	public function add_register_styles( $styles ) {

		$styles['genesis-overrides-css'] = new WPDDL_style( 'genesis-overrides-css', WPDDL_RES_RELPATH . '/css/themes/genesis-overrides.css', array(), WPDDL_VERSION, 'screen' );

		return $styles;
	}

	public function frontend_enqueue() {
		do_action( 'toolset_enqueue_styles', array( 'genesis-overrides-css' ) );
	}

	protected function run_hooks() {
		add_filter( 'toolset_add_registered_styles', array( &$this, 'add_register_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
	}
}