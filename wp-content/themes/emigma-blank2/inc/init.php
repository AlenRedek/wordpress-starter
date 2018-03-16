<?php
/**
 * emigma setup theme
 *
 * @package emigma
 */
 
/*
******************************************************************************************************
	Main settings
******************************************************************************************************
*/
update_option('siteurl', WP_SITEURL);
update_option('home', WP_HOME);

update_option('blog_public', BLOG_PUBLIC);

/*
******************************************************************************************************
	Set the content width based on the theme's design and stylesheet.
******************************************************************************************************
*/
if ( ! isset( $content_width ) ) {
	$content_width = 1110;
}

// Set the content width for full width pages with no sidebar.
if ( ! function_exists('emigma_content_width') ) {
	add_action( 'template_redirect', 'emigma_content_width' );
	function emigma_content_width() {
		if ( is_page_template( 'page-fullwidth.php' ) || is_page_template( 'front-page.php' ) ) {
			global $content_width;
			$content_width = 1110; /* pixels */
		}
	}
}

/*
******************************************************************************************************
	Sets up theme defaults and registers support for various WordPress features.
******************************************************************************************************
*/
if ( ! function_exists( 'emigma_setup' ) ) {
	add_action( 'after_setup_theme', 'emigma_setup' );
	function emigma_setup() {
		load_theme_textdomain( 'emigma', get_template_directory() . '/languages' );

		register_nav_menus( array(
			'primary'      		=> __( 'Primary Menu', 'emigma' ),
			'social-menu'		=> __( 'Social Menu', 'emigma' ),
			'footer-links' 		=> __( 'Footer Links', 'emigma' ), // secondary menu in footer
		));

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		//add_theme_support( 'woocommerce' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption' ));
		add_theme_support( 'post-formats', array( 'gallery' , 'video', ));
		add_theme_support( 'custom-background', apply_filters( 'emigma_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));
		add_theme_support( 'custom-logo', array(
			'flex-height' => true,
			'header-text' => array( 'site-title', 'site-description' )
		));

		add_image_size( 'theme-featured', 1920, 550, true );
		add_image_size( 'tab-small', 60, 60 , true); // Small Thumbnail
		add_image_size( 'gallery-thumbnail', 720, 480, true );
		add_image_size( 'post-thumbnail', 720, 400, true );
	}
}

/*
******************************************************************************************************
	Enqueue scripts & styles
******************************************************************************************************
*/
if ( ! function_exists('bootstrapBasicEnqueueScripts') ) {
    add_action( 'wp_enqueue_scripts', 'bootstrapBasicEnqueueScripts' );
    function bootstrapBasicEnqueueScripts() {
        $theme_info = wp_get_theme();

        wp_enqueue_style('main-style', get_stylesheet_uri());
  		wp_enqueue_style('theme-fonts', roman_custom_fonts()); // Loads google fonts
		wp_enqueue_style('ekko-lightbox', get_template_directory_uri().'/assets/css/vendor/ekko-lightbox.min.css' );
        wp_enqueue_style('helper-style', get_stylesheet_directory_uri() . '/assets/css/helper.css', array(), $theme_info->get( 'Version' ));
        wp_enqueue_style('theme-style', get_stylesheet_directory_uri() . '/assets/css/theme.css', array(), $theme_info->get( 'Version' ));

        wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-easing', get_template_directory_uri() . '/assets/js/vendor/jquery.easing.1.3.js', array('jquery'), '1.3', true);
		wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/vendor/bootstrap.min.js', array('jquery'), '3.3.7', true);
		wp_enqueue_script('bootstrap-hover', get_template_directory_uri() . '/assets/js/vendor/bootstrap-hover-dropdown.min.js', array('jquery', 'bootstrap'), '2.2.1', true);
		wp_enqueue_script('ekko-lightbox', get_template_directory_uri() . '/assets/js/vendor/ekko-lightbox.min.js', array('jquery'), '4.0.2', true);
		wp_enqueue_script('share', get_template_directory_uri() . '/assets/js/share.js', array('jquery'), false, true);
		wp_enqueue_script('theme-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), $theme_info->get( 'Version' ), true);

		if((is_home() || is_front_page()) && of_get_option('emigma_slider_checkbox') == 1) {
			wp_enqueue_style('flexslider', get_template_directory_uri().'/assets/css/vendor/flexslider.css');
			wp_enqueue_script('flexslider', get_template_directory_uri() . '/assets/js/vendor/flexslider.min.js', array('jquery'), '2.5.0', true );
		}
		if (class_exists( 'jigoshop')) { // Jigoshop specific styles loaded only when plugin is installed
			wp_enqueue_style('jigoshop-css', get_template_directory_uri().'/assets/css/vendor/jigoshop.css' );
		}
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        $ga_tracking_id = get_field('ga_tracking_id', 'options');
	    if($ga_tracking_id){
	    	wp_enqueue_script('ga_tracking', get_stylesheet_directory_uri() . '/assets/js/ga_tracking.js', array(), $theme_info->get( 'Version' ), false);
	    	wp_localize_script('ga_tracking', 'ga_tracking_id', $ga_tracking_id);
	    }
	    $typekit_id = get_field('typekit_id', 'options');
	    if($typekit_id){
	    	wp_enqueue_script('typekit-src', 'https://use.typekit.net/'.$typekit_id.'.js', array(), $theme_info->get( 'Version' ), false);
	    	wp_enqueue_script('typekit', get_stylesheet_directory_uri() . '/assets/js/typekit.js', array(), $theme_info->get( 'Version' ), false);
	    }
    }
}

/*
******************************************************************************************************
	Add HTML5 shiv and Respond.js for IE8 support of HTML5 elements and media queries
******************************************************************************************************
*/
if (!function_exists('emigma_ie_support_header')) {
	add_action( 'wp_head', 'emigma_ie_support_header', 11 );
	function emigma_ie_support_header() {
		echo '<!--[if lt IE 9]>'. "\n";
		echo '<script src="' . esc_url( get_template_directory_uri() . '/assets/js/vendor/html5shiv.min.js' ) . '"></script>'. "\n";
		echo '<script src="' . esc_url( get_template_directory_uri() . '/assets/js/vendor/respond.min.js' ) . '"></script>'. "\n";
		echo '<![endif]-->'. "\n";
	}
}

/*
******************************************************************************************************
	Enqueue admin scripts & styles
******************************************************************************************************
*/
if (!function_exists('adminEnqueueScripts')) {
	add_action('admin_head', 'adminEnqueueScripts');
	function adminEnqueueScripts() {
		wp_enqueue_style('admin_styles' , get_template_directory_uri().'/assets/css/admin.css');
	}
}

/*
******************************************************************************************************
	Add Excerpt support to pages
******************************************************************************************************
*/
add_action( 'init', 'add_excerpts_to_pages' );
function add_excerpts_to_pages() {
	unregister_taxonomy_for_object_type('post_tag', 'post');
	remove_post_type_support('post', 'post-formats');
	add_post_type_support( 'page', 'excerpt' );
}

/*
******************************************************************************************************
	Add custom font sizes
******************************************************************************************************
*/
if ( ! function_exists('mh_tinymce_settings') ) {
	add_filter('tiny_mce_before_init', 'mh_tinymce_settings');
	function mh_tinymce_settings($arr){
		$arr['block_formats'] = 'Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Paragraph=p';
		return $arr;
	}
}

/*
******************************************************************************************************
    Remove unneeded admin menu pages
******************************************************************************************************
*/
if ( ! function_exists('remove_menu_items') ) {
    if ( is_admin() ) {
        add_action('admin_menu', 'remove_menu_items', 1000);
        function remove_menu_items() {
        	remove_menu_page( 'edit-comments.php' );
            if ( ! (current_user_can('administrator')) ) {
            	remove_menu_page( 'aam' );
                remove_menu_page( 'jetpack' );
                remove_menu_page( 'wpcf7' );
            }
        }
    }
}

/*
******************************************************************************************************
	Resizing all image media files on upload to 1920
******************************************************************************************************
*/
if ( ! function_exists('ar_handle_upload') ) {
	add_filter( 'wp_handle_upload', 'ar_handle_upload' );
	function ar_handle_upload ( $params ){
	    $filePath = $params['file'];

	    if ( (!is_wp_error($params)) && file_exists($filePath) && in_array($params['type'], array('image/png','image/gif','image/jpeg'))){
	        $quality                        = 100;
	        list($largeWidth, $largeHeight) = array( 1920, 0 );
	        list($oldWidth, $oldHeight)     = getimagesize( $filePath );
	        list($newWidth, $newHeight)     = wp_constrain_dimensions( $oldWidth, $oldHeight, $largeWidth, $largeHeight );

	        $resizeImageResult = image_resize( $filePath, $newWidth, $newHeight, false, null, null, $quality);

	        unlink( $filePath );

	        if ( !is_wp_error( $resizeImageResult ) ){
	            $newFilePath = $resizeImageResult;
	            rename( $newFilePath, $filePath );
	        }
	        else{
	            $params = wp_handle_upload_error($filePath, $resizeImageResult->get_error_message());
	        }
	    }
	    return $params;
	}
}

/*
******************************************************************************************************
	Custom Page Columns
******************************************************************************************************
*/
add_filter('manage_pages_custom_column', 'mh_pageNewColumns', 10, 2);
if ( ! function_exists( 'mh_pageNewColumns' ) ) {
	function mh_pageNewColumns($column, $postid){
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

add_action('manage_pages_columns', 'mh_pageCustomColumns', 99, 2);
if ( ! function_exists( 'mh_pageCustomColumns' ) ) {
	function mh_pageCustomColumns($columns){
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

/*
******************************************************************************************************
	ACF THeme options page
******************************************************************************************************
*/
if(function_exists('acf_add_options_page')){
	acf_add_options_page(array(
		'page_title' 	=> __('Theme options', 'emigma'),
		'menu_title'	=> __('Theme options', 'emigma'),
		'menu_slug' 	=> 'site-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}

/*
******************************************************************************************************
	Yoast SEO breadcrumbs modifications for single pages
******************************************************************************************************
*/
if ( ! function_exists( 'ar_wpseo_breadcrumb_links' ) ) {
	add_filter( 'wpseo_breadcrumb_links', 'ar_wpseo_breadcrumb_links' );
	function ar_wpseo_breadcrumb_links( $links ) {
	    if(is_single()) {
	        $cpt_object = get_post_type_object(get_post_type());
	        $landing_page = get_page_id($cpt_object->name);
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
    Add additional data to Yoast's SEO JSON-LD output
******************************************************************************************************
*/
if ( ! function_exists('modify_wpseo_json_ld_output') ) {
  add_filter('wpseo_json_ld_output', 'modify_wpseo_json_ld_output', 10, 1);
  function modify_wpseo_json_ld_output( $data ) {
    switch($data["@type"]){
      case 'WebSite':
        $data["author"] = array(
        	'@id' => base64_decode('aHR0cHM6Ly93d3cucmVkZWsubWUvI2FsZW5yZWRlaw==')
        );
        $data['publisher'] = array(
			'@type' => 'Organization',
			'name' => 'Emigma Multimedia Lab',
			'url' => 'https://emigma.com/'
		);
      break;
    }

   return $data;

  }
}

/*
******************************************************************************************************
	Redirect non logged users to login page
******************************************************************************************************
*/
if ( ! function_exists( 'private_content_redirect_to_login' ) ) {
	add_action('template_redirect', 'private_content_redirect_to_login', 9);
	function private_content_redirect_to_login() {
		global $wp_query,$wpdb;
		if (is_404()) {
			$private = $wpdb->get_row($wp_query->request);
			$location = wp_login_url($_SERVER['REQUEST_URI']);
			if( 'private' == $private->post_status ) {
				wp_safe_redirect($location);
				exit;
			}
		}
	}
}

?>