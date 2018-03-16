<?php
/**
 * understrap functions and definitions
 *
 * @package emigma
 */

/*
****************************************************************************************************************
	Theme setup and custom theme supports.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/init.php')){
	require_once(get_stylesheet_directory() . '/inc/init.php');
}

/*
****************************************************************************************************************
	Theme global functions.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/global-functions.php')){
	require_once(get_stylesheet_directory() . '/inc/global-functions.php');
}

/*
****************************************************************************************************************
	Theme specific functions.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/theme-functions.php')){
	require_once(get_stylesheet_directory() . '/inc/theme-functions.php');
}

/*
****************************************************************************************************************
	Google fonts for blank theme.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/google-fonts.php')){
	require_once get_stylesheet_directory() . '/inc/wp/google-fonts.php';
}

/*
****************************************************************************************************************
	Custom functions that act independently of the theme templates.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/extras.php')){
	require_once get_stylesheet_directory() . '/inc/extras.php';
}

/*
****************************************************************************************************************
	File attachments
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/ar_attachmentsClass.php')){
	require_once(get_template_directory() . '/inc/ar_attachmentsClass.php');
}

/*
****************************************************************************************************************
	Register widget area.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/widgets/widgets.php')){
	require_once get_stylesheet_directory() . '/inc/widgets/widgets.php';
}

/*
****************************************************************************************************************
	Load custom WordPress nav walker.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/bootstrap-wp-navwalker.php')){
	require_once get_stylesheet_directory() . '/inc/wp/bootstrap-wp-navwalker.php';
}

/*
****************************************************************************************************************
	Load custom WordPress gallery.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/bootstrap-wp-gallery.php')){
	require_once get_stylesheet_directory() . '/inc/wp/bootstrap-wp-gallery.php';
}

/*
****************************************************************************************************************
	Implement the Custom Header feature.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/custom-header.php')){
	require_once get_stylesheet_directory() . '/inc/wp/custom-header.php';
}

/*
****************************************************************************************************************
	Customizer additions.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/customizer.php')){
	require_once get_stylesheet_directory() . '/inc/wp/customizer.php';
}

/*
****************************************************************************************************************
	Load Jetpack compatibility file.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/jetpack.php')){
	require_once get_stylesheet_directory() . '/inc/wp/jetpack.php';
}

/*
****************************************************************************************************************
	Jigoshop related functions
****************************************************************************************************************
*/
if(class_exists( 'jigoshop' ) && file_exists(get_stylesheet_directory() . '/inc/wp/jigoshop-setup.php')){
	require_once get_stylesheet_directory() . '/inc/wp/jigoshop-setup.php';
}

/*
****************************************************************************************************************
	Metabox file load
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/metaboxes.php')){
	require_once get_stylesheet_directory() . '/inc/wp/metaboxes.php';
}

/*
****************************************************************************************************************
	Load functions to secure your WP install.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/security.php')){
	require_once get_stylesheet_directory() . '/inc/wp/security.php';
}

/*
****************************************************************************************************************
	Custom template tags for this theme.
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/wp/template-tags.php')){
	require_once get_stylesheet_directory() . '/inc/wp/template-tags.php';
}

/*
****************************************************************************************************************
	WooCommerce related functions
****************************************************************************************************************
*/
if(class_exists( 'woocommerce' ) && file_exists(get_stylesheet_directory() . '/inc/wp/woo-setup.php')){
	require_once get_stylesheet_directory() . '/inc/wp/woo-setup.php';
}

/*
****************************************************************************************************************
	Load Contact form 7 Hooks
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/cf7_hooks.php')){
	require_once get_stylesheet_directory() . '/inc/cf7_hooks.php';
}

/*
****************************************************************************************************************
	Mailchimp
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory(). '/inc/mailchimp/mailchimp.php')){
	require_once(get_stylesheet_directory(). '/inc/mailchimp/mailchimp.php');
}

/*
****************************************************************************************************************
	Loadmore
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory(). '/inc/loadmore/loadmore.php')){
	require_once(get_stylesheet_directory(). '/inc/loadmore/loadmore.php');
}

/*
****************************************************************************************************************
	Queries
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/ar_queryClass.php')){
	global $qury;
	require_once get_stylesheet_directory() . '/inc/ar_queryClass.php';
	$qury = AR_Query_Class::getInstance();
}

/*
****************************************************************************************************************
	Queries
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/ar_youtubeClass.php')){
	global $youtube;
	require_once get_stylesheet_directory() . '/inc/ar_youtubeClass.php';
	$youtube = new AR_Youtube_Class;
}

/*
****************************************************************************************************************
	Custom Post Types
****************************************************************************************************************
*/
if(file_exists(get_stylesheet_directory() . '/inc/cpt/mh_WPCptClass.php')){
	global $cptf;
	require_once get_stylesheet_directory() . '/inc/cpt/mh_WPCptClass.php';
	$cptArray = array(
	    'event' => 'mhEventCpt',
	    'media' => 'mhMediaCpt',
	);
	$hideMenu = array();
	$cptf = new CptClass($cptArray, $hideMenu);
}

/*
****************************************************************************************************************
	Google Maps
****************************************************************************************************************
*/
$gmaps_dir = '/inc/gmaps';
if(file_exists(get_stylesheet_directory(). $gmaps_dir . '/ar_gmapsClass.php')){
    global $ar_gmaps;
	require_once(get_stylesheet_directory(). $gmaps_dir . '/ar_gmapsClass.php');
	$ar_gmaps = new AR_GMaps_Class($gmaps_dir, 'pharmacy');
}

?>