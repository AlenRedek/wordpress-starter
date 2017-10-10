<?php
	
if(file_exists(get_stylesheet_directory().'/includes/init.php')){
    require_once(get_stylesheet_directory().'/includes/init.php');
}

if(file_exists(get_stylesheet_directory().'/includes/theme-functions.php')){
    require_once(get_stylesheet_directory().'/includes/theme-functions.php');
}

/*
******************************************************************************************************
	Load custom cells types for Layouts plugin from the /dd-layouts-cells/ directory
******************************************************************************************************
*/
if ( defined('WPDDL_VERSION') && ! function_exists( 'include_ddl_layouts' ) ) {

	function include_ddl_layouts( $tpls_dir = '' ) {
		$dir_str = dirname( __FILE__ ) . $tpls_dir;
		$dir     = opendir( $dir_str );

		while ( ( $currentFile = readdir( $dir ) ) !== false ) {
			if ( is_file( $dir_str . $currentFile ) ) {
				include $dir_str . $currentFile;
			}
		}
		
		closedir( $dir );
	}

	include_ddl_layouts( '/includes/dd-layouts-cells/' );
}

?>