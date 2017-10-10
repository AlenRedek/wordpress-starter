<?php
/** POST IMPORT HOOKS HERE
 *  FUNCTIONS HOOKED HERE ARE FOUND IN /includes/main_functions/post-import-functions.php
 *  THESE HOOKS RUNS AFTER A CLEAN IMPORT
 *  USE THESE HOOKS FOR POST-IMPORT PROCESSING
 *  
 *  - START
 */

/**
 * Refactored since @2.0.6
 * Post import hooked method => priority
 */
$post_import_hooks	= array(
		
		//Record the refsites origin slug for checking purposes
		'wpvdemo_refsites_origin_slug'							=> 1,
		
		//Update CRED user form setting for redirect URL in membership layouts
		'wpvdemo_update_membership_layouts_redirect_url'		=> 5,
		
		//Let's import download manager media files and re-assemble it at the target uploads directory
		'wpvdemo_reassemble_downloads_manager_protected_media'	=> 9,
		
		//In Discover-WP where WPML is network activated, we need to disable theme localization for non-multilingual imports
		'wpvdemo_reset_wpml_nonmultilingual' 					=> 10,
		
		//After import, we need to update the URL of the Bootcommerce layout logout link
		'wpvdemo_update_bcl_logout_link'						=> 10,
		
		//After import, we need to update the URLs inside the Content of Simple Toolset Reference Site
		'wpvdemo_simple_refsite_url_content'					=> 10,
		
		//After import, we need to update to attachments to standalone since its different from multisite
		'wpvdemo_simple_refsite_update_attachments'				=> 10,
		
		//Let's assigned a unique My Account settings page for Classifieds layouts
		'wpvdemo_import_download_manager_settings'				=> 10,
		
		//Let's import download manager plugin settings for some sites needing it.
		'wpvdemo_classifieds_layouts_my_account_page'			=> 10,
		
		//Let's set WooCommerce attributes for BootCommerce site
		'wpvdemo_bootcommerce_layouts_attributes'				=> 10,
		
		//Let's search and replace reference site URl inside Layouts settings
		'wpvdemo_search_replace_hostnames_inside_layouts'		=> 18,
		
		//Let's search and replace no image URL in Toolset Classifieds site with Layouts
		'wpvdemo_search_replace_noimage_classifieds'			=> 19,
		
		//Let's removed unneeded notices after import
		'wpvdemo_remove_wpml_related_notices'					=> 10,
		
		//Let's patch the issue on assigning 'where to display at' for Types
		'wpvdemo_whereto_display_field_groups'					=> 10,
		
		//Let's double check the integrity of WPML settings after import for multilingual sites
		'wpvdemo_wpmlsettings_integrity_check'					=> 30,
		
		//Import ICL adl settings for sites that requires this
		'wpvdemo_import_icl_settings'							=> 50,
		
		//Some sites requiring discussion settings to be imported
		'wpvdemo_import_wp_discussionsettings_func'				=> 10,
		
		//Some sites requiring reading settings to be imported
		'wpvdemo_import_wp_readingsettings_func'				=> 10,
		
		//Fix for any parametric issues inside Layouts after import
		'wpvdemo_adjust_parametric_filter_settings_func'		=> 10,
		
		//Adjust Toolset starter theme mods correctly after import
		'wpvdemo_adjust_toolset_starter_mods_func'				=> 60,
		
		//Adjust WooCommerce shop page Layouts assignment after import
		'wpvdemo_adjust_woocommerce_shop_page_layouts_func'		=> 10,
		
		//Adjust WooCommerce product image gallery IDs after import
		'wpvdemo_adjust_woocommerce_productimage_gallery'		=> 10,
		
		//Check WooCommerce product CT assignments after import
		'wpvdemo_check_product_template_afterimport'			=> 99,
		
		//After importing multilingual site, let's assign the user as the translator
		'wpvdemo_assign_user_as_translator'						=> 10,

		//After importing multilingual site, let's adjust the element_ids of post_dd_layouts
		'wpvdemo_adjust_elementidspost_dd_layout'				=> 10,
		
		//After importing multilingual site with CRED, let's adjust the domain_name_context_md5 of the strings that reflects new CRED form IDS
		'wpvdemo_adjust_context_md5_cred'						=> 10,
		
		//After importing multilingual site, refresh translation IDs in translation status table with correct imported ids
		'wpvdemo_adjust_translation_status_table_tids'			=> 11,
		
		//Let's replace any pre-import URLs not being handled...
		'wpvdemo_universal_search_and_replace'					=> 200,
		
		//Let's replace any pre-import URLs not being handled...
		'wpvdemo_import_nav_menu_options_func'					=> 15,
		
		//Let's update any menu terms used with Toolset Layouts custom menu widgets cell...
		'wpvdemo_adjust_nav_menu_layouts_custom_menu_func'		=> 90,
		
		//Let's update any menu terms used with WPML settings...
		'wpvdemo_adjust_nav_menu_wpml_terms_func'				=> 99,
		
		//Let's update any widgets used with WPML after import...
		'wpvdemo_adjust_widget_body_text_func'					=> 5,
		
		//Add a fallback workaround just in case the new Toolset Layouts 1.9 Bootstrap version is not imported natively by Toolset.
		'wpvdemo_automatic_bootstrap_version_adjustment_func'	=> 150,
		
		//Log refsites..
		'wpvdemo_log_refsites_to_toolset'						=> 250,
		
		//Let's marked the WPML string translation as setup complete because no need to run it after import
		'wpvdemo_wpml_st_setup_completed'						=> 300,
		
		//Import WCML settings from the refsites server.
		'wpvdemo_import_wcml_settings'							=> 305,
		
		//Clear of any string scanning notices after import
		'wpvdemo_clear_any_string_scanning'						=> 310,
	
		//Clear of any WooCommerce Views notices after import for Membership site -not needed
		'wpvdemo_clear_wcviews_notices_membership_site'			=> 315,
		
		//Clear unneeded widgets in WooCommerce sites Toolset starter 1.3.9+
		'wpvdemo_clear_unneeded_widgets_added_by_theme'			=> 10,
		
		//Clear wpml_language_switcher in new WPML 3.6.0
		'wpvdemo_clear_wpml_language_switcher'					=> 320,
		
		//@since 2.1.1 Update content layouts resources
		'wpvdemo_update_imported_content_layouts_resources'		=> 75,
		
		//@since 2.1.1 Import WordPress SEO settings for some sites
		'wpvdemo_import_wordpress_seo_settings'					=> 30,

		//@since 2.1.2 Import Easy fancybox settings for some sites
		'wpvdemo_import_easyfancybox_settings'					=> 31,
		
		//@since 2.1.3 Handle post unassignments layouts-1516 workaround
		'wpvdemo_unassign_layouts_for_shop'						=> 888,

		//@since 2.1.4 Set 'wcml_products_to_sync'
		'wpvdemo_set_wcml_products_to_sync'						=> 998,
		
		//@since 2.1.1 Remove unintentional 'uncategorized'
		'wpvdemo_remove_uncategorized_post_cat'					=> 999
);

/**
 * Add all post import hooks
 */
foreach ( $post_import_hooks as $post_import_hooked_method	=> $post_import_hook_priority ) {	
	add_action('wpv_demo_import_finishing',$post_import_hooked_method, $post_import_hook_priority, 1 );	
}

/** POST IMPORT HOOKS HERE
 *
 * - END
 */