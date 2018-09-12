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

            // Emigma Hub
            
            array(
                'name'     				=> 'Purgatorio', // The plugin name
                'slug'     				=> 'purgatorio', // The plugin slug (typically the folder name)
                'source'   				=> 'https://github.com/EmigmaLab/purgatorio/archive/master.zip', // The plugin source
                'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
                'version' 				=> '1.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
                'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
                'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
                'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
            ),
            array(
                'name'     				=> 'Advanced Custom Fields PRO',
                'slug'     				=> 'advanced-custom-fields-pro',
                'source'   				=> 'http://hub.razvija.se/wp-plugins/advanced-custom-fields-pro.zip',
                'required' 				=> true
            ),
            array(
                'name'     				=> 'Gravity Forms',
                'slug'     				=> 'gravityforms',
                'source'   				=> 'http://hub.razvija.se/wp-plugins/gravityforms.zip',
                'required' 				=> true
            ),
            array(
                'name'     				=> 'Gravity Forms Multilingual',
                'slug'     				=> 'gravityforms-multilingual',
                'source'   				=> 'http://hub.razvija.se/wp-plugins/gravityforms-multilingual.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'WPML Multilingual CMS',
                'slug'     				=> 'sitepress-multilingual-cms',
                'source'   				=> 'http://hub.razvija.se/wp-plugins/sitepress-multilingual-cms.zip',
                'required' 				=> true
            ),
            array(
                'name'     				=> 'WPML Media',
                'slug'     				=> 'wpml-media-translation',
                'source'   				=> 'http://hub.razvija.se/wp-plugins/wpml-media-translation.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'WPML String Translation',
                'slug'     				=> 'wpml-string-translation',
                'source'   				=> 'http://hub.razvija.se/wp-plugins/wpml-string-translation.zip',
                'required' 				=> true
            ),
            array(
                'name'     				=> 'WPML Translation Management',
                'slug'     				=> 'wpml-translation-management',
                'source'   				=> 'http://hub.razvija.se/wp-plugins/wpml-translation-management.zip',
                'required' 				=> true
            ),

            // WordPress Repository
            array(
                'name'     				=> 'Cookie Notice',
                'slug'     				=> 'cookie-notice',
                'source'   				=> 'https://downloads.wordpress.org/plugin/cookie-notice.zip',
                'required' 				=> true
            ),
            array(
                'name'     				=> 'Custom Post Type UI',
                'slug'     				=> 'custom-post-type-ui',
                'source'   				=> 'https://downloads.wordpress.org/plugin/custom-post-type-ui.zip',
                'required' 				=> true
            ),
            array(
                'name'     				=> 'Duplicate Post',
                'slug'     				=> 'duplicate-post',
                'source'   				=> 'https://downloads.wordpress.org/plugin/duplicate-post.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'Go Live Update URLS',
                'slug'     				=> 'go-live-update-urls',
                'source'   				=> 'https://downloads.wordpress.org/plugin/go-live-update-urls.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'Image Regenerate & Select Crop',
                'slug'     				=> 'image-regenerate-select-crop',
                'source'   				=> 'https://downloads.wordpress.org/plugin/image-regenerate-select-crop.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'KingComposer',
                'slug'     				=> 'kingcomposer',
                'source'   				=> 'https://downloads.wordpress.org/plugin/kingcomposer.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'Radio Buttons for Taxonomies',
                'slug'     				=> 'radio-buttons-for-taxonomies',
                'source'   				=> 'https://downloads.wordpress.org/plugin/radio-buttons-for-taxonomies.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'Redirection',
                'slug'     				=> 'redirection',
                'source'   				=> 'https://downloads.wordpress.org/plugin/redirection.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'Simple Custom Post Order',
                'slug'     				=> 'simple-custom-post-order',
                'source'   				=> 'https://downloads.wordpress.org/plugin/simple-custom-post-order.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'What The File',
                'slug'     				=> 'what-the-file',
                'source'   				=> 'https://downloads.wordpress.org/plugin/what-the-file.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'Wordfence Security',
                'slug'     				=> 'wordfence',
                'source'   				=> 'https://downloads.wordpress.org/plugin/wordfence.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'WordPress Importer',
                'slug'     				=> 'wordpress-importer',
                'source'   				=> 'https://downloads.wordpress.org/plugin/wordpress-importer.zip',
                'required' 				=> false
            ),
            array(
                'name'     				=> 'Yoast SEO',
                'slug'     				=> 'wordpress-seo',
                'source'   				=> 'https://downloads.wordpress.org/plugin/wordpress-seo.zip',
                'required' 				=> false
            )

        );

        tgmpa( $plugins );
    }
}

?>