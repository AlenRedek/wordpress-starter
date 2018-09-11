<?php

$class_plugin_activation_path = dirname(__FILE__) . '/class-tgm-plugin-activation.php';

if( ! file_exists($class_plugin_activation_path) ){
    return;
}

require_once $class_plugin_activation_path;

/**
 * Register the required plugins for this theme.
 *
 */
if( ! function_exists('theme_register_required_plugins') ){
    add_action( 'tgmpa_register', 'theme_register_required_plugins' );
    function theme_register_required_plugins() {
        /**
         * Array of plugin arrays. Required keys are name and slug.
         * If the source is NOT from the .org repo, then source is also required.
         */
        $plugins = array(

            array(
                'name'     				=> 'Cookie Notice', // The plugin name
                'slug'     				=> 'cookie-notice', // The plugin slug (typically the folder name)
                'source'   				=> 'https://downloads.wordpress.org/plugin/cookie-notice.1.2.44.zip', // The plugin source
                'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
                'version' 				=> '1.2.44', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
            )

        );

        tgmpa( $plugins );
    }
}

?>