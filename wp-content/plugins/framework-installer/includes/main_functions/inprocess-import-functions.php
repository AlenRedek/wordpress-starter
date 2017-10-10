<?php
/** INPROCESS IMPORT FUNCTIONS HERE
 *  ORIGINAL DEFNITION OF HOOKS FOR THESE FUNCTIONS ARE FOUND IN /includes/inprocess-import-hooks.php
 *  THESE HOOKS RUNS WHILE FRAMEWORK INSTALLER IS PROCESSING IMPORT
 *  THESE ARE THE HOOKED FUNCTIONS TO THESE IN-PROCESS IMPORT HOOKS
 *  do_action('wpvdemo_import_before_step_' . $step, $settings);
 *  do_action('wpvdemo_import_after_step_' . $step, $settings); 
 */

/**
 * @since    2.0.5
 * Re-create Download Manager history table after import step 1 (required for membership site)
 */
function wpvdemo_create_download_history_table(  $settings ) {

	if ( ( ! (empty ( $settings ) ) ) && ( isset( $settings ) ) ) {
		$settings_array	= (array) $settings;
		if ( isset( $settings_array['download_url'] ) ) {
			$download_url	= $settings_array['download_url'];
			$download_url	= (string) ( $download_url );
			$refsite_slug	= basename( $download_url );
			
			require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
			$sites_covered = apply_filters('wpvdemo_sites_with_download_manager',array() );
			if ( array_key_exists( $refsite_slug, $sites_covered ) ) {
				if ( method_exists('WPDM\WordPressDownloadManager', 'Install' ) ) {
					(new WPDM\WordPressDownloadManager)->Install();
				}
			}			
		}
	}
}

/**
 * @since 2.0.6
 * frameworkinstaller-254:
 * Set empty array for string packges during in process import of multilingual sites to prevent PHP errors
 */
function wpvdemo_set_empty_string_packages_inprocess_import( $arg1, $arg2 ) {
	
	$check_import_is_done_connected = get_option ( 'wpv_import_is_done' );	
	if ( ( false === $check_import_is_done_connected ) && ( wpvdemo_wpml_is_enabled() ) ) {
		/** Import is ongoing and WPML is active*/
		$arg1	= array();
	}
	
	return $arg1;
}

/**
 * @since 2.1.4
 * frameworkinstaller-308: Reimport Access because it requires Layouts to be imported first.
 */
function wpvdemo_reimport_types_access(  $settings ) {	
	if ( ( ! (empty ( $settings ) ) ) && ( isset( $settings ) ) ) {
		$settings_array	= (array) $settings;
		if ( isset( $settings_array['download_url'] ) ) {
			$download_url	= $settings_array['download_url'];
			$download_url	= (string) ( $download_url );
			$refsite_slug	= basename( $download_url );			
			require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
			$sites_covered = apply_filters('wpvdemo_sites_requiring_access_reimport',array() );
			if ( in_array( $refsite_slug, $sites_covered ) ) {
				$overwrite	= true;
				$success = wpvdemo_import_access($settings->download_url, $settings, $overwrite );				
			}
		}
	}
}