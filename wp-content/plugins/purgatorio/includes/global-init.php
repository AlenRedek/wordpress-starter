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
    Theme init actions
******************************************************************************************************
*/
if( ! function_exists('pg_init_actions') ){
	add_action('init', 'pg_init_actions');
	function pg_init_actions() {
		add_post_type_support( 'page', 'excerpt' );
		
		// Debugging options
		if(function_exists('pg_is_dev')){
			if( pg_is_dev() ){
				ini_set('xdebug.var_display_max_depth', 20);
				ini_set('xdebug.var_display_max_children', 256);
				ini_set('xdebug.var_display_max_data', 5000);
				ini_set('max_execution_time', 120); // Siteground's max
			}
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
    Custom font sizes on WYSIWYG
******************************************************************************************************
*/
if ( ! function_exists('pg_tinymce_settings') ) {
    add_filter('tiny_mce_before_init', 'pg_tinymce_settings');
    function pg_tinymce_settings($arr){
        $arr['block_formats'] = 'Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Paragraph=p';
        return $arr;
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
        if ( ! (current_user_can('administrator')) ) {
			// Remove unneeded admin menu pages
			//remove_menu_page( 'jetpack' );
			//remove_menu_page( 'wpcf7' );
			remove_menu_page('vc-welcome');
			remove_menu_page( 'aam' );
        }
    }
}

/*
******************************************************************************************************
    Remove Rev Slider Metabox
******************************************************************************************************
*/
if ( ! function_exists('pg_remove_rev_slider_mb') ) {
    if ( is_admin() && class_exists('RevSliderFront') ) {
        add_action( 'do_meta_boxes', 'pg_remove_rev_slider_mb' );
        function pg_remove_rev_slider_mb() {
            remove_meta_box( 'mymetabox_revslider_0', get_post_type(), 'normal' );
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

        if(function_exists('pg_is_dev')){
            if( pg_is_dev() ){
                $classes[] = 'development';
            }else{
                $classes[] = 'production';
            }
        }
        
        // Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

        if(function_exists('pll_current_language')){
            $classes[] = 'lang-'.pll_current_language();
        }
        
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
    Add additional data to Yoast's SEO JSON-LD output
******************************************************************************************************
*/
if ( ! function_exists('pg_modify_wpseo_json_ld_output') ) {
	add_filter('wpseo_json_ld_output', 'pg_modify_wpseo_json_ld_output', 10, 1);
	function pg_modify_wpseo_json_ld_output( $data ) {
		$pg_options = get_option( PURGATORIO__SETTINGS );
		switch($data["@type"]){
			case 'WebSite':
				if(isset($pg_options['author_id'])){
					$data['author'] = array(
						'@id' => $pg_options['author_id']
					);
				}
				if(isset($pg_options['publisher_id'])){
					$data['publisher'] = array(
						'@id' => $pg_options['publisher_id']
					);
				}
			break;
		}
		
		return $data;
	
	}
}

/*
******************************************************************************************************
	Yoast SEO breadcrumbs modifications for single pages
******************************************************************************************************
*/
if ( ! function_exists( 'pg_wpseo_breadcrumb_links' ) ) {
	add_filter( 'wpseo_breadcrumb_links', 'pg_wpseo_breadcrumb_links' );
	function pg_wpseo_breadcrumb_links( $links ) {
	    if(is_single() && function_exists('pg_get_cpt_page_id')) {
	        $cpt_object = get_post_type_object(get_post_type());
	        $landing_page = pg_get_cpt_page_id($cpt_object->name);
	        if( ! $cpt_object->_builtin && $landing_page ){
	        	foreach($links as $k=>$d){
	        		if(isset($d['ptarchive']) && $d['ptarchive'] == $cpt_object->name){
	        			$key = $k;
	        			break;
	        		}
	        	}
	        	if($key){
	        		array_splice( $links, $key, 1, array(
		                array(
		                    'id' => $landing_page
		                )
		            ));
	        	}
	        }
	    }
	    
	    return $links;
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

/*
******************************************************************************************************
	Custom Page Columns - Display page template PHP file location
******************************************************************************************************
*/
add_filter('manage_pages_custom_column', 'pg_pages_custom_column', 10, 2);
if ( ! function_exists( 'pg_pages_custom_column' ) ) {
	function pg_pages_custom_column($column, $postid){
		if($column == 'page_order'){
			if($p = get_post($postid)){
				$pn = get_post_ancestors($postid);
				$pn = array_reverse($pn);
				if(count($pn)){
					foreach($pn as $n){
						$pt = get_post($n);
						echo $pt->menu_order.'. ';
					}
				}
				echo $p->menu_order;
			}
		}else if($column == 'template'){
			if($t = get_page_template_slug($postid)){
				$templates = get_page_templates();
				if($t_name = array_search($t, $templates)){
					echo $t_name;
					echo '<br /><span style="color:#9a9a9a">'.$t.'</span>';
				}
			}else echo '<span style="color:#ccc;">'.__('Default', 'gledalisce').'</span>';
		}
	}
}

add_action('manage_pages_columns', 'pg_manage_pages_columns', 99, 2);
if ( ! function_exists( 'pg_manage_pages_columns' ) ) {
	function pg_manage_pages_columns($columns){
		$cols = array();
		if(is_array($columns)){
			foreach($columns as $k=>$c){
				$cols[$k] = $c;
				if($k == 'title'){
					$cols['page_order'] = __('Order', 'emigma');
					$cols['template'] = __('Template', 'emigma');
				}
			}
			return $cols;
		}else
			return $columns;
	}
}

?>