<?php
	
/*
******************************************************************************************************
	Update Site Address (URL) & Search Engine Visibility options
******************************************************************************************************
*/
if(defined(WP_SITEURL) && get_option('siteurl') != WP_SITEURL){
	update_option('siteurl', WP_SITEURL);
}
if(defined(WP_HOME) && get_option('home') != WP_HOME){
	update_option('home', WP_HOME);
}
if(defined(BLOG_PUBLIC) && get_option('blog_public') != BLOG_PUBLIC){
	update_option('blog_public', BLOG_PUBLIC);
}

/*
******************************************************************************************************
    Plugin init actions
******************************************************************************************************
*/
if( ! function_exists('pg_init_actions') ){
	add_action('init', 'pg_init_actions');
	function pg_init_actions() {
		add_post_type_support( 'page', 'excerpt' );
		
		// Debugging options
		if( pg_is_dev() ){
			ini_set('xdebug.var_display_max_depth', 20);
			ini_set('xdebug.var_display_max_children', 256);
			ini_set('xdebug.var_display_max_data', 5000);
			ini_set('max_execution_time', 120); // Siteground's max
		}
	}
}

/*
******************************************************************************************************
    Add Meta Tags in Header
******************************************************************************************************
*/
if ( ! function_exists('pg_head') ) {
    add_action('wp_head', 'pg_head');
    function pg_head() {
	    // Prevent users from zoomig website on touch screen devices
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>';
        
        // Add HTML5 shiv and Respond.js for IE8 support of HTML5 elements and media queries
        echo '<!--[if lt IE 9]>'. "\n";
		echo '<script src="' . esc_url( PURGATORIO__PLUGIN_URL . 'assets/js/vendor/html5shiv.min.js' ) . '"></script>'. "\n";
		echo '<script src="' . esc_url( PURGATORIO__PLUGIN_URL . 'assets/js/vendor/respond.min.js' ) . '"></script>'. "\n";
		echo '<![endif]-->'. "\n";
    }
}

/*
******************************************************************************************************
    Enqueue theme scripts & styles
******************************************************************************************************
*/
if ( ! function_exists('pg_enqueue_styles_scripts') ) {
	add_action('wp_enqueue_scripts', 'pg_enqueue_styles_scripts', 20);
	function pg_enqueue_styles_scripts() {
		// Remove Open Sans from frontend which is added by WP itself
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );
	}
}

/*
******************************************************************************************************
    Sets up theme defaults and registers support for various WordPress features.
******************************************************************************************************
*/
if ( ! function_exists( 'pg_after_setup_theme' ) ) {
    add_action( 'after_setup_theme', 'pg_after_setup_theme' );
    function pg_after_setup_theme() {
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ));
        add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery', 'video', 'quote', 'link' ));
		add_theme_support( 'custom-logo' );
		// Adding support for Widget edit icons in customizer
		add_theme_support( 'customize-selective-refresh-widgets' );
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		
		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'pg_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
    }
}

/*
******************************************************************************************************
    Modify admin menu
******************************************************************************************************
*/
if ( ! function_exists('pg_admin_menu') ) {
    add_action('admin_menu', 'pg_admin_menu', 1000);
    function pg_admin_menu() {
        if ( ! current_user_can('administrator') ) {
			// Remove unneeded admin menu pages
			remove_menu_page('vc-welcome');
			remove_menu_page( 'aam' );
        }
    }
}

/*
******************************************************************************************************
    Adds custom classes to body
******************************************************************************************************
*/
if ( ! function_exists('pg_body_class') ) {
    add_filter( 'body_class', 'pg_body_class' );
    function pg_body_class( $classes ) {

        if( pg_is_dev() ){
            $classes[] = 'dev';
        }else{
            $classes[] = 'prod';
        }
        
        // Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}
		
        $classes[] = 'lang-'.pg_get_current_language();
        
        // Removes tag class from the body_class array to avoid Bootstrap markup styling issues.
        foreach ( $classes as $key => $value ) {
			if ( 'tag' == $value ) {
				unset( $classes[ $key ] );
			}
		}

        return $classes;
    }
}

/*
******************************************************************************************************
    Disable premium plugins update notification
******************************************************************************************************
*/
if ( ! function_exists('pg_update_plugins') ) {
	add_filter( 'site_transient_update_plugins', 'pg_update_plugins' );
	function pg_update_plugins( $value ) {
		if(isset($value->response['revslider/revslider.php'])) unset( $value->response['revslider/revslider.php'] );
		if(isset($value->response['js_composer/js_composer.php'])) unset( $value->response['js_composer/js_composer.php'] );
		if(isset($value->response['LayerSlider/layerslider.php'])) unset( $value->response['LayerSlider/layerslider.php'] );
	    if(isset($value->response['go_pricing/go_pricing.php'])) unset( $value->response['go_pricing/go_pricing.php'] );
	    
	    return $value;
	}
}

/*
******************************************************************************************************
    Resizing all image media files on upload to 1920
******************************************************************************************************
*/
if ( ! function_exists('pg_handle_upload') ) {
    add_filter( 'wp_handle_upload', 'pg_handle_upload' );
    function pg_handle_upload ( $params ){
        $filePath = $params['file'];

        if ( (!is_wp_error($params)) && file_exists($filePath) && in_array($params['type'], array('image/png','image/gif','image/jpeg'))){
            $quality                        = 100;
            list($largeWidth, $largeHeight) = array( 1920, 0 );
            list($oldWidth, $oldHeight)     = getimagesize( $filePath );
            list($newWidth, $newHeight)     = wp_constrain_dimensions( $oldWidth, $oldHeight, $largeWidth, $largeHeight );

            $resizeImageResult = image_resize( $filePath, $newWidth, $newHeight, false, null, null, $quality);

            unlink( $filePath );

            if ( ! is_wp_error( $resizeImageResult ) ){
                $newFilePath = $resizeImageResult;
                rename( $newFilePath, $filePath );
            }else{
                //$params = wp_handle_upload_error($filePath, $resizeImageResult->get_error_message());
            }
        }
        return $params;
    }
}

?>