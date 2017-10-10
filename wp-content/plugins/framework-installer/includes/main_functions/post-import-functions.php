<?php
/** POST IMPORT FUNCTIONS HERE
 *  ORIGINAL DEFNITION OF HOOKS FOR THESE FUNCTIONS ARE FOUND IN /includes/post-import-hooks.php
 *  THESE HOOKS RUNS AFTER A CLEAN IMPORT
 *  USE THESE FUNCTIONS FOR POST-IMPORT PROCESSING
 */

function wpvdemo_reset_wpml_nonmultilingual($file) {
	global $frameworkinstaller, $wpdb;
	if (is_object ( $frameworkinstaller )) {
		if (method_exists ( $frameworkinstaller, 'is_discoverwp' )) {
			$is_discover = $frameworkinstaller->is_discoverwp ();
			if ($is_discover) {
				// Here we need to check for any non-multilingual sites
				if (function_exists ( 'wpvdemo_has_wpml_implementation' )) {
					$has_wpml = wpvdemo_has_wpml_implementation ( $file, false );
					if (! ($has_wpml)) {
						// Is not multilingual, reset WPML to avoid the corrupt message
						$current_settings = get_option ( 'icl_sitepress_settings' );
						if ($current_settings) {
							
							unset ( $current_settings ['setup_complete'] );
							unset ( $current_settings ['setup_wizard_step'] );
							unset ( $current_settings ['existing_content_language_verified'] );
							unset ( $current_settings ['dont_show_help_admin_notice'] );
							
							global $wpdb;
							$wpdb->query ( 'TRUNCATE TABLE ' . $wpdb->prefix . 'icl_translations' );
							
							update_option ( 'icl_sitepress_settings', $current_settings );
						}
					}
				}
			}
		}
	}
}
function wpvdemo_update_bcl_logout_link($file) {
	if (! (empty ( $file ))) {
		
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_update_bcl_logout_link',array());
		
		if (in_array($refsite_slug,$sites_covered)) {
			
			// BootCommerce Layouts
			// Let's retrieved the imported ID of the 'Logout' navigation menu item
			
			global $wpdb;
			$posttable = $wpdb->posts;		
			$logout_id = $wpdb->get_var ( "SELECT ID from $posttable where post_title='Logout' AND post_type='nav_menu_item'" );
			
			// Validate
			$logout_id = intval ( $logout_id );
			if ($logout_id > 0) {
				 
				// Step3, Get WooCommerce My Account page ID
				$myaccount_pageid= get_option ( 'woocommerce_myaccount_page_id' );
				$myaccount_pageid= intval($myaccount_pageid);
			
				// Step4, get WooCommerce logout endpoint
				$wc_logout_endpoint= get_option( 'woocommerce_logout_endpoint' );
				$wc_logout_endpoint=trim($wc_logout_endpoint);
			
				if (($myaccount_pageid > 0) && (!(empty($wc_logout_endpoint)))) {
					 
					// Step5, Generated post import logout link url
					$wc_my_account_url = get_permalink ( $myaccount_pageid );
					$wc_my_account_url=strtok($wc_my_account_url,'?');
					$wc_my_account_url = rtrim ( $wc_my_account_url, '/' );
					$after_import_logout_url = $wc_my_account_url . '/'.$wc_logout_endpoint.'/';
			
					// Step6, Update to dB
					$success_updating = update_post_meta ( $logout_id, '_menu_item_url', $after_import_logout_url );
					$original_lang='en';
					 
					// Step7, Check if importing multilingual version
					if (( defined( 'ICL_SITEPRESS_VERSION' )) &&  ( (defined( 'WPML_ST_VERSION' ) ))) {
						
						$strings_table = $wpdb->prefix.'icl_strings';
						$string_translation_table = $wpdb->prefix.'icl_string_translations';
						
						//Get active languages
						$active_languages= apply_filters( 'wpml_active_languages', NULL );
						
						if ((!(empty($active_languages))) && (is_array($active_languages))) {
							foreach ($active_languages as $k=>$v) {
								if ($k != $original_lang) {
									//Not in original language
										
									//Step 8: Get the equivalent translated My Account page ID
									$translated_myaccount_page_id= apply_filters( 'wpml_object_id', $myaccount_pageid, 'page', FALSE, $k );
										
									//Step 9: Retrieved translation for custom logout endpoint
									$logout_string_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $strings_table WHERE context='WordPress' AND value = %s",
											$wc_logout_endpoint));
									$logout_string_id =intval($logout_string_id );
									if ($logout_string_id > 0) {
										$translated_logout_endpoint = $wpdb->get_var($wpdb->prepare("SELECT value FROM $string_translation_table WHERE string_id=%d",
												$logout_string_id));
																				
										//Step 10: Get equivalent translated My Account permalink in this language
										$translated_wc_my_account_url = get_permalink ( $translated_myaccount_page_id );
										$translated_wc_my_account_url=strtok($translated_wc_my_account_url,'?');
										$translated_wc_my_account_url = rtrim ( $translated_wc_my_account_url, '/' );
										$basename_account_translated= basename($translated_wc_my_account_url);
										$site_url=site_url();
										$translated_wc_my_account_url=$site_url.'/'.$k.'/'.$basename_account_translated;
										
										//Step11: Get translated ID of logout
										$translated_logout_id= apply_filters( 'wpml_object_id', $logout_id, 'nav_menu_item', FALSE, $k );
										
										//Step12: Compose URL									
										$translated_import_logout_url = $translated_wc_my_account_url . '/'. $translated_logout_endpoint.'/';
											
										//Step13, Update to dB
										$success_updating = update_post_meta ( $translated_logout_id, '_menu_item_url', $translated_import_logout_url );
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
function wpvdemo_simple_refsite_url_content($file) {
	if (! (empty ( $file ))) {
		
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_postimport_replace_urls',array());
		
		if (in_array($refsite_slug,$sites_covered)) {
			
			// Applies to Simple Toolset Ref Site with or without Layouts
			global $frameworkinstaller;
			$is_discover = $frameworkinstaller->is_discoverwp ();
			$site_url_imported = get_bloginfo ( 'url' );
			
			if (! ($is_discover)) {
				// Standalone import
				// not multisite, fix image URLs
				fix_image_urls_bootstrap_vanilla_standalone ( $site_url_imported );
				
			} elseif ($is_discover) {
				// Discover-WP import
				// Let's check the child site source in ref site
				$child_site_source = $frameworkinstaller->wpvdemo_refsite_child_site_source ( $file );
				fix_image_urls_bootstrap_vanilla_standalone ( $child_site_source );
			}
		}
	}
}
function wpvdemo_classifieds_layouts_my_account_page($file) {
	if (! (empty ( $file ))) {
		
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_classifieds_layouts_my_account_page',array());
		
		if (in_array($refsite_slug,$sites_covered)) {		
			// Classifieds layouts
			
			global $wpdb;
			$posttable = $wpdb->posts;
			$my_account_settings_page_id = $wpdb->get_var ( "SELECT ID from $posttable where post_name='my-account-settings' AND post_content='[woocommerce_my_account]'" );
			
			$my_account_settings_page_id = intval ( $my_account_settings_page_id );
			if ($my_account_settings_page_id > 0) {
				
				$result = update_option ( 'woocommerce_myaccount_page_id', $my_account_settings_page_id );
			}
		}
	}
}
function wpvdemo_bootcommerce_layouts_attributes($file) {
	if (! (empty ( $file ))) {

		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_bootcommerce_layouts_attributes',array());
		
		if (in_array($refsite_slug,$sites_covered)) {
			// We are importing BootCommerce Layouts site			
			global $wpdb;
			
			/* Fix issues on WooCommerce color attributes not imported */
			// Define WooCommerce product attributes
			$wc_attribute = array (
					'attribute_label' => 'Color',
					'attribute_name' => 'color',
					'attribute_type' => 'select',
					'attribute_orderby' => 'menu_order' 
			);
			
			$success_wc_attribute_insert = $wpdb->insert ( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $wc_attribute );
			
			// Clear transient
			delete_option ( '_transient_wc_attribute_taxonomies' );
		}
	}
}
function wpvdemo_simple_refsite_update_attachments($file) {
	if (! (empty ( $file ))) {		
		
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_simple_refsite_update_attachments',array());
		
		if (in_array($refsite_slug,$sites_covered)) {

			// Applies to Simple Toolset Ref Site with or without Layouts
			// Let's trace the origin
			if (defined ( 'WPVDEMO_DOWNLOAD_URL' )) {
				$download_url = WPVDEMO_DOWNLOAD_URL;
				if (! (empty ( $download_url ))) {
					// Download defined
					$parsed_url = parse_url ( $download_url );
					if (isset ( $parsed_url ['host'] )) {
						$original_host = $parsed_url ['host'];
						
						// Formulate blogs.dir files directory version (multisite version)						
						$blogsdir_path = 'http://' . $original_host . '/'.$refsite_slug.'/files';
						
						// Formulate current uploads directory
						$uploads_constants_of_this_site = wp_upload_dir ();
						$correct_uploads_url_image_path = $uploads_constants_of_this_site ['baseurl'];
						
						global $wpdb;
						
						// search and replace
						$success_replace = $wpdb->query ( $wpdb->prepare ( "
										UPDATE $wpdb->posts
										SET post_content = replace(post_content,'%s','%s')
										", $blogsdir_path, $correct_uploads_url_image_path ) );
					}
				}
			}
		}
	}
}
function wpvdemo_search_replace_hostnames_inside_layouts($file) {
	if (! (empty ( $file ))) {
		
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_search_replace_hostnames_inside_layouts',array());
		
		if (in_array($refsite_slug,$sites_covered)) {		

			// Affects sites with Layouts
			// Step1, retrieved all Layouts imported
			$active_site_layouts = wpvdemo_retrieve_all_published_layouts ();
			
			// Step2, let's defined the origin and target
			if (defined ( 'WPVDEMO_DOWNLOAD_URL' )) {
				// Standalone import
				$download_url = WPVDEMO_DOWNLOAD_URL;
				if (! (empty ( $download_url ))) {
					// Download defined
					$parsed_url = parse_url ( $download_url );
					if (isset ( $parsed_url ['host'] )) {
						$original_host = $parsed_url ['host'];
						$dirname = basename ( dirname ( $file ) );
						
						// Formulate blogs.dir files directory version (multisite version)
						// Origin
						$search_this = 'http://' . $original_host . '/' . $dirname . '/files';
						
						// Target
						$uploads_constants_of_this_site = wp_upload_dir ();
						$replace_with = $uploads_constants_of_this_site ['baseurl'];
					}
				}
				
				// Step3, if has Layouts, do search and replace
				if ($active_site_layouts) {
					// Process dd_layouts_settings replacements
					$layouts_result = wpvdemo_handle_dd_layouts_settings_custom_replacement ( $active_site_layouts, $original_host, $search_this, $replace_with );
				}
			}
		}
	}
}
function wpvdemo_search_replace_noimage_classifieds($file) {
	if (! (empty ( $file ))) {
		
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_search_replace_noimage_classifieds',array());
		
		//Now applies to both standalone and discover-wp import
		//WIth support for multilingual versions added
		if (in_array($refsite_slug,$sites_covered)) {
			global $frameworkinstaller;

			// This is standalone import
			// Dont hardcode wp-content ,let WP determines it.
			$uploads_constants_of_this_site = wp_upload_dir ();
			$correct_uploads_url_image_path = $uploads_constants_of_this_site ['baseurl'];

			// From ref sites
			$source_refsite=wpvdemo_determine_source_refsite();
			if ($source_refsite) {
					
				$noimage_refsite_source='http://'.$source_refsite. '/' . $refsite_slug;
				$blogsdir_path = $noimage_refsite_source.'/files/2015/02/no-image.jpg';
				
				// Correct: '/wp-content/uploads/2015/02/no-image.jpg'
				$no_image_standard_path = $correct_uploads_url_image_path . '/2015/02/no-image.jpg';
				
				// search and replace in dB for Content Templates
				global $wpdb;
				$success_replace = $wpdb->query ( $wpdb->prepare ( "
													UPDATE $wpdb->posts
													SET post_content = replace(post_content,'%s','%s') WHERE post_type='view-template'
													", $blogsdir_path, $no_image_standard_path ) );
			}
		}
	}
}
function wpvdemo_remove_wpml_related_notices($file) {
	if (function_exists ( 'wpvdemo_has_wpml_implementation' )) {
		$has_wpml = wpvdemo_has_wpml_implementation ( $file, false );
		if ($has_wpml) {
			// Has WPML implementation
			$icl_admin_messages = get_option ( 'icl_admin_messages' );
			if ($icl_admin_messages) {
				$update_needed	=	false;
				if ((isset ( $icl_admin_messages ['messages'] ['_st_string_in_wrong_context_warning'] ['hidden'] ))) {
					// Set, ensure dismiss is TRUE
					$icl_admin_messages ['messages'] ['_st_string_in_wrong_context_warning'] ['hidden'] = TRUE;
					$update_needed	=	true;
				}
				
				/**
				 * frameworkinstaller-213
				 * Further removed any unneeded notices after import
				 */
				if ((isset ( $icl_admin_messages ['messages'] ['wcml-woocommerce-default-currency-changed'] ['hidden'] ))) {
					// Set, ensure dismiss is TRUE
					$icl_admin_messages ['messages'] ['wcml-woocommerce-default-currency-changed'] ['hidden'] = TRUE;
					$update_needed	= true;
				}				
				
				// Let's update back IF needed
				if ( true === $update_needed ) {
					update_option ( 'icl_admin_messages', $icl_admin_messages );					
				}				
			}
		}
	}
}
function wpvdemo_whereto_display_field_groups($file) {
	if (! (empty ( $file ))) {
		
		// Applies to all sites with Types groups
		// Step 1, let's retrieve all imported Types group IDs
		global $wpdb;
		$posttable = $wpdb->posts;
		$group_ids = $wpdb->get_results ( "SELECT ID FROM $posttable WHERE post_type='wp-types-group' AND post_status='publish'", ARRAY_A );
		$clean_group_ids = wpvdemo_all_purpose_id_cleaner_func ( $group_ids );
		
		// Step2, let's verify if we have imported content templates
		$processed_ct= get_option('wpv_demo_processed_ct_ids');
		if (($processed_ct) && (!(empty($processed_ct)))) {
			
			// Step3, let's looped through the group IDS
			if ((is_array ( $clean_group_ids )) && (! (empty ( $clean_group_ids )))) {
				foreach ( $clean_group_ids as $k => $group_id ) {
					$group_id = intval ( $group_id );
					if ($group_id > 0) {
											
						// Step4, let's retrieved the group templates associated with this group
						$group_templates = get_post_meta ( $group_id, '_wp_types_group_templates', TRUE );
						if ((! (empty ( $group_templates ))) && ('all' != $group_templates)) {
							
							// This block is only executed if group templates is not empty AND not set to 'all
							// Step5, let's explode the settings
							$exploded = explode ( ",", $group_templates );
							if ((is_array ( $exploded )) && (! (empty ( $exploded )))) {
								
								// Let's looped through the templates
								$processed_data=array();
								foreach ( $exploded as $k => $template_id ) {
									if (! (empty ( $template_id ))) {										
										$old_template_id = intval ( $template_id );
										if ($old_template_id > 0) {
											//int format
											//Let's retrieved the equivalent imported CT
											if (isset($processed_ct[$old_template_id])) {
												$new_template_id=$processed_ct[$old_template_id];
												$processed_data[]=$new_template_id;
											} else {
												//Not correctly imported, bail out
												break;
											}
										} else {
											//Not anymore integer, could be slug now, bail out
											break;
											
										}
									} else {
										$processed_data[]=$template_id;
									}
								}
								//Done looping
								if ((is_array ( $processed_data )) && (! (empty ( $processed_data )))) {
									$comma_separated = implode(",", $processed_data);
									//Update back
									update_post_meta($group_id, '_wp_types_group_templates', $comma_separated);
								}																
							}
						}
					}
				}
			}
		}
	}
	
}
function wpvdemo_wpmlsettings_integrity_check($file) {
	if (function_exists ( 'wpvdemo_has_wpml_implementation' )) {
		$has_wpml = wpvdemo_has_wpml_implementation ( $file, false );
		if ($has_wpml) {
			// Has WPML implementation
			$icl_settings_backup =  get_option ( 'icl_sitepress_backup_settings' );
			$icl_settings_current = get_option ( 'icl_sitepress_settings' );
				
			if (($icl_settings_backup) && ($icl_settings_current) ){
				$icl_settings_backup_serialized =serialize($icl_settings_backup);
				$icl_settings_current_serialized =serialize($icl_settings_current);
				if ($icl_settings_backup_serialized == $icl_settings_current_serialized) {
				} else {
					//Unequal, restore the original WPML imported settings...
					update_option ( 'icl_sitepress_settings', $icl_settings_backup );
				}
			}
		}
	}
}

function wpvdemo_refsites_origin_slug($file) {
	
	if (!(empty($file))) {
		
		$dirname = wpvdemo_get_refsites_slug_func($file);
		update_option('wpvdemo_refsites_origin_slug',$dirname);		
		
	}	
}

//OK-Import ICL adl settings
function wpvdemo_import_icl_settings($file) {
	if (! (empty ( $file ))) {
	
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_site_requires_icladlsettings',array());
	
		if (in_array($refsite_slug,$sites_covered)) {
			//Retrieved dirname
			$dirname_refsite= dirname ( $file );
			$xml_file = $dirname_refsite . '/wpml_icl_adl_settings.xml';

			// Parse remote XML
			$data = wpv_remote_xml_get ( $xml_file );
			if (! ($data)) {
				return;
			}
			$xml = simplexml_load_string ( $data );
			$import_data = wpv_admin_import_export_simplexml2array ( $xml );			

			//Let's update
			if ((is_array($import_data)) && (!(empty($import_data)))) {
				$option_name='icl_adl_settings';
				delete_option($option_name);
				update_option($option_name,$import_data);
			}

		}
	}	
}

//OK-Import discussion settings for some sites
function wpvdemo_import_wp_discussionsettings_func($file) {
	if (! (empty ( $file ))) {
	
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_site_requires_discussion_settings',array());
	
		if (in_array($refsite_slug,$sites_covered)) {
			
			//Retrieved dirname
			$dirname_refsite= dirname ( $file );
			$xml_file = $dirname_refsite . '/wp_discussion_settings.xml';
			
			// Parse remote XML
			$data = wpv_remote_xml_get ( $xml_file );
			if (! ($data)) {
				return;
			}
			$xml = simplexml_load_string ( $data );
			$import_data = wpv_admin_import_export_simplexml2array ( $xml );
			
			//Let's update
			if ((is_array($import_data)) && (!(empty($import_data)))) {

				foreach ($import_data as $the_option_name=>$the_option_value) {
					
					update_option($the_option_name,$the_option_value);
				}
			}			
		}
	}	
}
//OK-Import reading settings for some sites
function wpvdemo_import_wp_readingsettings_func($file) {
	if (! (empty ( $file ))) {

		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_site_requires_reading_settings',array());

		if (in_array($refsite_slug,$sites_covered)) {

			//Retrieved dirname
			$dirname_refsite= dirname ( $file );
			$xml_file = $dirname_refsite . '/wp_reading_settings.xml';

			// Parse remote XML
			$data = wpv_remote_xml_get ( $xml_file );
			if (! ($data)) {
				return;
			}
			$xml = simplexml_load_string ( $data );
			$import_data = wpv_admin_import_export_simplexml2array ( $xml );

			//Let's update
			if ((is_array($import_data)) && (!(empty($import_data)))) {

				foreach ($import_data as $the_option_name=>$the_option_value) {

					update_option($the_option_name,$the_option_value);
				}
			}
		}
	}
}
function wpvdemo_adjust_parametric_filter_settings_func($file) {
	if (! (empty ( $file ))) {	
		$updated_parametric_after_import= get_option('wpvdemo_updated_parametric_filters');
		if (!($updated_parametric_after_import)) {
			//Needs update
			$refsite_slug = wpvdemo_get_refsites_slug_func($file);
			require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
			$sites_covered= apply_filters('wpvdemo_site_requires_parametric_filter_adjustment',array());
			
			if (array_key_exists($refsite_slug,$sites_covered)) {
			
			global $wpddlayout;
		
			//Retrieved settings, an array of affected layouts belonging to this site
			$settings= $sites_covered[$refsite_slug];
			if ((is_array($settings)) && (!(empty($settings)))) {
				 
				foreach ($settings as $k=>$v) {
					//Retrieved needle
					$the_parameters= $settings[$k];
					$needle= key($the_parameters);
						
					//Retrieved target page
					$target_page= reset($the_parameters);
					$target_page_id= WPDD_Layouts::get_post_ID_by_slug( $target_page, 'page' );
						
					//Get layout ID by its slug
					$layout_id=$wpddlayout->get_layout_id_by_slug( $k );
					$layout_id=intval($layout_id);
					if ($layout_id > 0) {
						$layout_settings= WPDD_Layouts::get_layout_settings_raw_not_cached( $layout_id);
						if (isset($layout_settings->Rows)) {
							$Rows=$layout_settings->Rows;
							if ((is_array($Rows)) && (!(empty($Rows)))) {
								//Loop through Rows
								foreach ($Rows as $k_rows=>$v_rows) {
									//$v_rows is object, check for Cells
									if (isset($v_rows->Cells)) {
										$Cells=$v_rows->Cells;
										//Loop through $Cells
										foreach ($Cells as $k_cells=>$v_cells) {
											$cell_name= $v_cells->name;
											if ($needle == $cell_name) {
												//Found, access 'content
												if (isset($v_cells->content)) {
													$content=$v_cells->content;
													//Access 'widget'
													if (isset($content->widget)) {
														$widget=$content->widget;
														if (isset($widget->target_id)) {
															//Assemble
															$layout_settings->Rows[$k_rows]->Cells[$k_cells]->content->widget->target_id=$target_page_id;
															//Save settings
															WPDD_Layouts::save_layout_settings( $layout_id, $layout_settings );
															break;
														}
													}
												}
											} else {
												//Not found, check for inner rows
												if (isset($v_cells->Rows)) {
													//Inner rows
													$innerRows=$v_cells->Rows;
													foreach ($innerRows as $k_innerrows=>$v_innerrows) {
														if (isset($v_innerrows->Cells)) {
															$innerCells= $v_innerrows->Cells;
															foreach ($innerCells as $kinnercells=>$vinnercells) {
																$innercellname= $vinnercells->name;
																if ($needle == $innercellname) {
																	//Found, access 'content
																	if (isset($vinnercells->content)) {
																		$content=$vinnercells->content;
																		//Access 'widget'
																		if (isset($content->widget)) {
																			$widget=$content->widget;
																			if (isset($widget->target_id)) {
																				//Assemble
																				$layout_settings->Rows[$k_rows]->Cells[$k_cells]->Rows[$k_innerrows]->Cells[$kinnercells]->content->widget->target_id=$target_page_id;
																				//Save settings
																				WPDD_Layouts::save_layout_settings( $layout_id, $layout_settings );
																				break;
																			}
																		}
																	}
																}
															}
														}
													}
												}
											}
										}
									}
								}
			
							}
						}
					}
				}
				
				//At this point, we are done
				update_option('wpvdemo_updated_parametric_filters','yes');
			}
			}
		}
	}	 
}
function wpvdemo_adjust_toolset_starter_mods_func($file) {
	
	//Let's checked if this site is using Toolset starter
	$active_theme = wp_get_theme();
	$active_theme_name= $active_theme->get( 'Name' ) ;
	
	if ('Toolset Starter' == $active_theme_name) {
		
		$mods_to_update = array(
				'ref_color_styles',
				'ref_theme_styles',
				'ref_wc_styles',
				);
		
		foreach ($mods_to_update as $k=>$v) {
			$mod_settings_value= get_theme_mod($v);
			if (is_string($mod_settings_value)) {
				//String value
				if (('1' === $mod_settings_value) || ('0' === $mod_settings_value)) {
					
					//Convert to integer so styling settings will be rendered correctly
					$mod_settings_value=intval($mod_settings_value);
					
					//Update back
					set_theme_mod( $v, $mod_settings_value );
				}
			}			
		}		
	}	
}
function wpvdemo_adjust_woocommerce_shop_page_layouts_func($file) {
	if (! (empty ( $file ))) {
	
		$refsite_slug = wpvdemo_get_refsites_slug_func($file);
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered= apply_filters('wpvdemo_update_wc_shoppage_layouts',array());
	
		if (in_array($refsite_slug,$sites_covered)) {
				
			//Step1. Retrieved the imported WooCommerce shop page ID
			$existing_shop_page = get_option ( 'woocommerce_shop_page_id' );
			$existing_shop_page = intval($existing_shop_page);
			if ($existing_shop_page > 0) {
				//Check if we have layouts template assigned
				$original_lang='en';
				$existing_layouts_template= get_post_meta($existing_shop_page,'_layouts_template',TRUE);
				if ($existing_layouts_template) {
					//exists					
					delete_post_meta($existing_shop_page, '_layouts_template');
					update_post_meta($existing_shop_page,'_wp_page_template','single.php');
				}			
				
				//Check if we are dealing with the multilingual version
				if (( defined( 'ICL_SITEPRESS_VERSION' )) &&  ( (defined( 'WPML_ST_VERSION' ) ))) {
					//Get active languages
					$active_languages= apply_filters( 'wpml_active_languages', NULL );
					
					if ((!(empty($active_languages))) && (is_array($active_languages))) {
						foreach ($active_languages as $k=>$v) {
							if ($k != $original_lang) {
								//Not in original language
								//Get the equivalent translated shop page ID
								$translated_shop_page_id= apply_filters( 'wpml_object_id', $existing_shop_page, 'page', FALSE, $k );
								$translated_shop_page_id=intval($translated_shop_page_id);
								if ($translated_shop_page_id > 0) {
									$existing_layouts_template_translation= get_post_meta($translated_shop_page_id,'_layouts_template',TRUE);
									if ($existing_layouts_template_translation) {
										//exists
										delete_post_meta($translated_shop_page_id, '_layouts_template');
										update_post_meta($translated_shop_page_id,'_wp_page_template','single.php');
									}									
								}								
							}
						}
					}
				}
							
			} 
		}
	}	
}
function wpvdemo_adjust_woocommerce_productimage_gallery($file) {
	if (! (empty ( $file ))) {
	
		//Applies to eCommerce sample sites only
		if (wpvdemo_woocommerce_is_active()) {
			
			//Step1, retrieved processed posts
			$processed_posts= get_option('wpvdemo_processed_posts_imported');
			if ((is_array($processed_posts)) && (!(empty($processed_posts)))) {
				//Step2, retrieved all products
				global $Class_WooCommerce_Views;
				
				if (method_exists($Class_WooCommerce_Views,'wc_views_get_all_product_ids_clean')) {
					$all_products= $Class_WooCommerce_Views->wc_views_get_all_product_ids_clean();
					
					//Step3, loop through products and check if set _product_image_gallery
					if ((is_array($all_products)) && (!(empty($all_products)))) {
						foreach ($all_products as $k=>$v) {
							$product_galleries= get_post_meta($v,'_product_image_gallery',TRUE);
							$product_galleries =trim($product_galleries);
							if ((!(empty($product_galleries))) && (is_string($product_galleries))) {
								
								//Explode old galleries
								$detailed_images = explode(",", $product_galleries);
								
								//Step4, loop through each image and adjust the Ids after import
								if ((is_array($detailed_images)) && (!(empty($detailed_images)))) {
									
									//Create updated images array
									$updated_images_id=array();
									foreach ($detailed_images as $k_image=>$v_image) {
										//$v_image is the old un-adjusted ID
										//Find its equivalent new imported version
										if (isset($processed_posts[$v_image])) {
											//imported version found
											$updated_images_id[]= $processed_posts[$v_image];
										}										
									}
									
									if ((is_array($updated_images_id)) && (!(empty($updated_images_id)))) {
										$comma_separated_detailed_images = implode(",", $updated_images_id);
										//We are done looping we are ready to update
										update_post_meta($v,'_product_image_gallery',$comma_separated_detailed_images);
									}
								}
							}
						}
					}
				}				
			}	
		}
	}	
}
function wpvdemo_check_product_template_afterimport($file) {
	
	if (! (empty ( $file ))) {
	
		//Applies to eCommerce sample sites only
		if (wpvdemo_woocommerce_is_active()) {
				
			//Step1, retrieved processed posts
			$template_equivalence= get_option('wpv_demo_template_equivalence');
			if ((is_array($template_equivalence)) && (!(empty($template_equivalence)))) {
				
				//Step2, retrieved all products
				global $Class_WooCommerce_Views;
	
				if (method_exists($Class_WooCommerce_Views,'wc_views_get_all_product_ids_clean')) {
					$all_products= $Class_WooCommerce_Views->wc_views_get_all_product_ids_clean();
						
					//Step3, loop through products and check if set _product_image_gallery
					if ((is_array($all_products)) && (!(empty($all_products)))) {
						//Step3, loop through products and check CT assignments after import
						foreach ($all_products as $k => $v) {
							if (isset($template_equivalence[$v])) {
								$correct_template_assignment= $template_equivalence[$v];
								$currently_assigned_template=get_post_meta($v,'_views_template',TRUE);	
								$correct_template_assignment =intval($correct_template_assignment);
								$currently_assigned_template = intval($currently_assigned_template);
								if (($correct_template_assignment > 0) && ($currently_assigned_template > 0)) {
									if ( $currently_assigned_template != $correct_template_assignment) {
										//Update
										update_post_meta($v,'_views_template',$correct_template_assignment);
									}									
								}
							}						
						}
					}
				}
			}
		}
	}
}
function wpvdemo_assign_user_as_translator($file) {
	
	if (wpvdemo_wpml_is_enabled()) {
		
		
		//Retrieve admin user information
		if (function_exists('get_current_user_id')) {			
			
			global $wpdb;
			$current_user_id=get_current_user_id();
			$updated=array();
			
			//ICL string status table
			$icl_string_status_table= $wpdb->prefix."icl_translation_status";
			$icl_translation_status_exist= $wpdb->get_var("SHOW TABLES LIKE '$icl_string_status_table'" );
				
			if ($icl_string_status_table == $icl_translation_status_exist) {
				
				$updated[]=$wpdb->query (
						$wpdb->prepare (
								"UPDATE $icl_string_status_table SET translator_id=%d",
								$current_user_id
						)
				);				
					
			}

			//ICL translate job table
			$icl_translatejob_table= $wpdb->prefix."icl_translate_job";
			$icl_translatejob_exist= $wpdb->get_var("SHOW TABLES LIKE '$icl_translatejob_table'" );
			
			if ($icl_translatejob_table == $icl_translatejob_exist) {
			
				$updated[]=$wpdb->query (
						$wpdb->prepare (
								"UPDATE $icl_translatejob_table SET translator_id=%d",
								$current_user_id
						)				
				);
				
				$updated[]=$wpdb->query (
						$wpdb->prepare (
								"UPDATE $icl_translatejob_table SET manager_id=%d",
								$current_user_id
						)
				);					
			}

			//ICL string translation job table
			$icl_stringtranslation_table= $wpdb->prefix."icl_string_translations";
			$icl_stringtranslation_exist= $wpdb->get_var("SHOW TABLES LIKE '$icl_stringtranslation_table'" );
				
			if ($icl_stringtranslation_table == $icl_stringtranslation_exist) {
					
				$updated[]=$wpdb->query (
						$wpdb->prepare (
								"UPDATE $icl_stringtranslation_table SET translator_id=%d",
								$current_user_id
						)
				);
			}

			/** Complete additional translator related configurations after import */
			global $frameworkinstaller;
			$frameworkinstaller->wpvdemo_complete_all_translator_configurations($current_user_id);
		}
		

	}
}
function wpvdemo_adjust_elementidspost_dd_layout($file) {
	
	//Requires Layouts and WPML
	//Step1, get all translation ids of post_dd_layouts
	if ((wpvdemo_wpml_is_enabled()) && (wpvdemo_layouts_is_active())) {
		global $wpdb;
		$icl_translations_table= $wpdb->prefix."icl_translations";
		$layouts_element_ids = $wpdb->get_results ( "SELECT translation_id,element_id FROM $icl_translations_table WHERE element_type='post_dd_layouts'", ARRAY_A );

		//Step2, get all Layouts imported to the wp_post table
		$layouts_post_imported= get_option('wpvdemo_preimport_layoutsid');
		
		//Step3, adjust Layouts element ids in icl_translations table based on imported ids
		if ((is_array($layouts_element_ids) && (!(empty($layouts_element_ids)))) && 
			(is_array($layouts_post_imported) && (!(empty($layouts_post_imported))))) {				
				foreach ($layouts_element_ids as $k=>$v) {
					
					//Get old Layouts ID not yet adjusted
					$old_layouts_id= $v['element_id'];
					
					//Get translation id
					$current_translation_id= $v['translation_id'];
					
					if (isset($layouts_post_imported[$old_layouts_id])) {
						
						//Equivalent imported ids found
						$equivalent_imported_id= $layouts_post_imported[$old_layouts_id];
						$equivalent_imported_id= intval($equivalent_imported_id);
						if ($equivalent_imported_id > 0) {
							$element_id_updated=$wpdb->query (
									$wpdb->prepare (
											"UPDATE $icl_translations_table SET element_id=%d WHERE translation_id=%d",
											$equivalent_imported_id,$current_translation_id
									)
							);
						}
					}
				}
				
			}		
	}
}
function wpvdemo_adjust_context_md5_cred($file) {
	
	//Step1, check if we are importing a site with CRED and WPML plugins activated
	if ((wpvdemo_cred_is_active()) && (wpvdemo_wpml_is_enabled())) {
		
		//Step2, check that all tables are ready	
		global $wpdb,$frameworkinstaller;
		$tables_ready=$frameworkinstaller->wpvdemo_validate_icl_required_db_tables();
		
		if ($tables_ready) {
			
			//Step3, get all strings for adjustment
			$icl_strings_table= $wpdb->prefix."icl_strings";
			$context_to_search = 'cred-form-';
			$like = "%$context_to_search%";
			$sql_query = "SELECT id,context,name,domain_name_context_md5 FROM $icl_strings_table WHERE context LIKE %s";				
			
			$string_results_query = $wpdb->get_results ( $wpdb->prepare ( $sql_query, $like ), ARRAY_A );
			if ($string_results_query) {		    
			    
			    //Step4, loop through all the strings
			    if ((!(empty($string_results_query))) && (is_array($string_results_query))) {
			    	foreach ($string_results_query as $k=>$string_details) {
			    		
			    		//Step5, get imported context and id
			    		$imported_string_id=$string_details['id'];
			    		$imported_context=$string_details['context'];
			    		
			    		//Step6, get imported name
			    		$imported_string_name=$string_details['name'];
			    		
			    		//Step7, retrieved imported domain name context md5
			    		$domain_name_context_md5=$string_details['domain_name_context_md5'];
			    		
			    		//Step8, compute correct domain_name_context_md5 based from imported values
			    		$correct_domain_name_md5context=md5($imported_context.$imported_string_name);
			    		
			    		//Step9, compare 
			    		if ($correct_domain_name_md5context != $domain_name_context_md5) {
			    			//Step10, if not the same string needs update
			    			$wpdb->query ( $wpdb->prepare ( "UPDATE $icl_strings_table SET domain_name_context_md5=%s WHERE id=%d", $correct_domain_name_md5context, $imported_string_id ) );
			    		}
			    		
			    	}
			    	
			    	/** Loops done and MD5 adjustment is done at this stage */
			    	/** Some CRED sites have very detailed context inside its CRED post body that we need to update after import*/
			    	$frameworkinstaller-> wpvdemo_search_replace_outdated_cred_context_in_body($file);			    	
			    }
			}			
		}
		

	}
}
function wpvdemo_universal_search_and_replace($file) {
	
	
	if(!class_exists('Framework_Installer_Migration_Search_Replace'))
	{
		require_once WPVDEMO_ABSPATH . '/classes/class-migration_search_replace_tool.php';
	}

	//Generate replaceables
	global $frameworkinstaller;	
	
	//Delete wpvdemo so it can be regenerated after this search and replace
	delete_option('wpvdemo-index-xml');
	$replaceable= $frameworkinstaller->wpvdemo_generate_replaceable_hostnames($file);	
	
	if ((!isset($fi_migration_search_replace)) && (!(empty($replaceable))))
	{
		//Instantiate class
		$fi_migration_search_replace= new Framework_Installer_Migration_Search_Replace();
		
		foreach ($replaceable as $k => $configuration) {
			$fi_migration_search_replace->Framework_Installer_Migration_Search_Replace($configuration);
		}		
	}
}

function wpvdemo_import_nav_menu_options_func($file) {

	/** Runs for all sites */
	//Retrieved dirname
	$dirname_refsite= dirname ( $file );
	$xml_file = $dirname_refsite . '/wp_nav_menu_options.xml';
	
	// Parse remote XML
	$data = wpv_remote_xml_get ( $xml_file );
	if (! ($data)) {
		return;
	}
	
	$xml = simplexml_load_string ( $data );
	$import_data = wpv_admin_import_export_simplexml2array ( $xml );

	//Let's update
	if ((is_array($import_data)) && (!(empty($import_data)))) {
		$option_name='nav_menu_options';
		
		//Extract setting
		if (isset($import_data['nav_menu_options'])) {
			
			//Setting set
			//Convert to array
			//Nav menu options import
			$nav_menu_options_imported= $import_data['nav_menu_options'];
			if (!(empty($nav_menu_options_imported))) {
				$nav_menu_options_imported=json_decode($nav_menu_options_imported,TRUE);
				if ((is_array($nav_menu_options_imported)) && (!(empty($nav_menu_options_imported)))) {
					
					//Adjust nav terms id by looping to any available settings at auto_add
					$terms_nav_imported=get_option('wpvdemo_processed_terms_imported');
					if ((isset($nav_menu_options_imported['auto_add'])) && ((is_array($terms_nav_imported)) && (!(empty($terms_nav_imported))))) {
						
						//Auto_add set
						$auto_add_setting=$nav_menu_options_imported['auto_add'];						
						if (is_array($auto_add_setting)) {
							foreach ($nav_menu_options_imported['auto_add'] as $k_autoadd=>$v_autoadd) {
								$refnavmenu_term_id=intval($v_autoadd);
								if ($refnavmenu_term_id > 0) {
									if (isset($terms_nav_imported[$refnavmenu_term_id])) {
										$new_imported_id=$terms_nav_imported[$refnavmenu_term_id];
										$new_imported_id=intval($new_imported_id);
										if ($new_imported_id > 0) {
											//Update
											$nav_menu_options_imported['auto_add'][$k_autoadd] = $new_imported_id;
										}
									}
								}
							}
						}
					}					
					delete_option($option_name);
					update_option($option_name,$nav_menu_options_imported);					
				}

			}
		}

	}	
}

/** 
 * When a menu is saved with WPML settings, let's adjust to the imported ID
 * Because terms ID can change after import
 * This is required for WPML language switcher to work after import (for those set with menu)
 */
function wpvdemo_adjust_nav_menu_wpml_terms_func($file) {
	
	if (wpvdemo_wpml_is_active()) {
		
		//WPML enabled
		//Get processed terms from dB		
		$wpvdemo_processed_terms=get_option('wpvdemo_processed_terms_imported');
		
		if ((is_array($wpvdemo_processed_terms)) && (!(empty($wpvdemo_processed_terms)))) {
			//Retrieved imported WPML settings
			$icl_sitepress_settings=get_option('icl_sitepress_settings');
			
			if ($icl_sitepress_settings) {
				if (isset($icl_sitepress_settings['menu_for_ls'])) {
					
					//menu_for_ls is set, retrieved
					//This ID is still not adjusted and still usin the original ID as it was created in the reference sites
			    	$menu_for_ls=$icl_sitepress_settings['menu_for_ls'];
			    	$menu_for_ls=intval($menu_for_ls);
			    	if ($menu_for_ls > 0) {
			    		
			    		//Let's check if the equivalent imported nav term ID
			    		if (isset($wpvdemo_processed_terms[$menu_for_ls])) {
			    			
			    			//Exist, retrieved
			    			$adjusted_nav_menu_term=$wpvdemo_processed_terms[$menu_for_ls];
			    			$adjusted_nav_menu_term_int=intval($adjusted_nav_menu_term);
			    			if ($adjusted_nav_menu_term_int > 0) {
			    				$icl_sitepress_settings['menu_for_ls']=$adjusted_nav_menu_term;
			    				//Save back
			    				update_option('icl_sitepress_settings',$icl_sitepress_settings);
			    			}
			    		}
			    	}
				}
				
				
			}
			
		}
		
		
	}
	
}
/**
 * Adjust widget body text used with WPML widgets after import
 * Replace \n with \r\n , changed during import. * 
 */
function wpvdemo_adjust_widget_body_text_func($file) {
	
	//Step1, check requisites, multilingual imports only
	if (wpvdemo_wpml_is_enabled()) {		

		//Step3, get widget text options
		$widget_text = get_option('widget_text');
		$widget_text_modified=$widget_text;
		if(is_array($widget_text_modified)){
			foreach($widget_text_modified as $k=>$w){
				if (is_array($w)) {
					if (isset($w['text'])) {
						$wtext=$w['text'];
						$wtext_updated = str_replace(array("\n"), "\r\n", $wtext);
						$widget_text_modified[$k]['text']= $wtext_updated;
					}
				}
			}
			
			//Update back only when widgets are changed
			if ($widget_text_modified != $widget_text) {
				
				//Arrays not equal, update
				update_option('widget_text',$widget_text_modified);
			}
			
		}
		
	}
}

/**
 * Adjust custom menu terms ID used inside Layouts after import
 */
function wpvdemo_adjust_nav_menu_layouts_custom_menu_func($file) {
	
	//Step1, check if we have Layouts enabled
	//Only applies to sites with Layouts
	if ( ( wpvdemo_layouts_is_active() ) && ( defined( 'WPDDL_LAYOUTS_SETTINGS') ) ) {
		
		//With layouts
		//Step2, retrieved all Layouts containing custom menu widget implementation
		global $wpdb;
		$post_meta_table	= $wpdb->prefix.'postmeta';
		$string_to_search	= 'widget_nav_menu';
		$like 				= "%$string_to_search%";
		$meta_key			= WPDDL_LAYOUTS_SETTINGS;
		$sql_query			= "SELECT post_id,meta_value FROM $post_meta_table WHERE meta_key='$meta_key' AND meta_value LIKE %s";			
		$layouts_array = $wpdb->get_results ( $wpdb->prepare ( $sql_query, $like ), ARRAY_A );
		$wpvdemo_processed_terms=get_option('wpvdemo_processed_terms_imported');
		
		if ((is_array($layouts_array)) && (!(empty($layouts_array)))) {
			//Step3, loop through each layouts
			foreach ($layouts_array as $k=>$v) {
				$layout_id=$v['post_id'];
				$layout_id=intval($layout_id);
				//Step4, get layout settings of this layout using Layouts setting retrieval method
				
				if ($layout_id > 0) {
					$layout_settings= WPDD_Layouts::get_layout_settings_raw_not_cached( $layout_id);
					if (isset($layout_settings->Rows)) {
						$Rows=$layout_settings->Rows;
						if ((is_array($Rows)) && (!(empty($Rows)))) {
							//Loop through Rows
							foreach ($Rows as $k_rows=>$v_rows) {
								//$v_rows is object, check for Cells
								if (isset($v_rows->Cells)) {
									$Cells=$v_rows->Cells;
									//Loop through $Cells
									foreach ($Cells as $k_cells=>$v_cells) {										
										if ((isset($v_cells->cell_type)) && ('widget-cell' ==$v_cells->cell_type )) {
											//Found, access 'content
											if (isset($v_cells->content)) {
												$content=$v_cells->content;
												//Access 'widget'
												if (isset($content->widget)) {
													$widget=$content->widget;
													if (isset($widget->nav_menu)) {
													
														$nav_menu_id_preimport=$widget->nav_menu;
														$nav_menu_id_preimport=intval($nav_menu_id_preimport);
													
														if ($nav_menu_id_preimport > 0) {
																
															//Let's retrieved the equivalent imported nav menu terms ID
															if (is_array($wpvdemo_processed_terms)) {
																if (isset($wpvdemo_processed_terms[$nav_menu_id_preimport])) {
																		
																	//Equivalent found, retrieved
																	$equivalent_imported_nav_menu=$wpvdemo_processed_terms[$nav_menu_id_preimport];
																	$equivalent_imported_nav_menu=intval($equivalent_imported_nav_menu);
																	if ($equivalent_imported_nav_menu > 0) {
																		
																		//Assemble
																		$layout_settings->Rows[$k_rows]->Cells[$k_cells]->content->widget->nav_menu=$equivalent_imported_nav_menu;
																		//Save settings
																		WPDD_Layouts::save_layout_settings( $layout_id, $layout_settings );
																		break;			

																	}
																}
															}
														}
													}							
												}
											}
										} else {
												
												//Not found, check for inner rows
												if (isset($v_cells->Rows)) {
													//Inner rows
													$innerRows=$v_cells->Rows;
													foreach ($innerRows as $k_innerrows=>$v_innerrows) {
														if (isset($v_innerrows->Cells)) {
															$innerCells= $v_innerrows->Cells;
															foreach ($innerCells as $kinnercells=>$vinnercells) {																
																if ((isset($vinnercells->cell_type)) && ('widget-cell' ==$vinnercells->cell_type )) {
																	//Found, access 'content
																	if (isset($vinnercells->content)) {
																		$content=$vinnercells->content;
																		//Access 'widget'
																		if (isset($content->widget)) {
																			$widget=$content->widget;
																			if (isset($widget->nav_menu)) {
																				
																				$nav_menu_id_preimport=$widget->nav_menu;
																				$nav_menu_id_preimport=intval($nav_menu_id_preimport);
																				
																				if ($nav_menu_id_preimport > 0) {
																					
																					//Let's retrieved the equivalent imported nav menu terms ID
																					if (is_array($wpvdemo_processed_terms)) {
																						if (isset($wpvdemo_processed_terms[$nav_menu_id_preimport])) {
																							
																							//Equivalent found, retrieved
																							$equivalent_imported_nav_menu=$wpvdemo_processed_terms[$nav_menu_id_preimport];
																							$equivalent_imported_nav_menu=intval($equivalent_imported_nav_menu);
																							if ($equivalent_imported_nav_menu > 0) {
																								
																								//Assemble
																								$layout_settings->Rows[$k_rows]->Cells[$k_cells]->Rows[$k_innerrows]->Cells[$kinnercells]->content->widget->nav_menu=$equivalent_imported_nav_menu;
																								
																								//Save settings
																								WPDD_Layouts::save_layout_settings( $layout_id, $layout_settings );
																								break;
																							}
																						}
																					}																				
																				}
																			}
																		}
																	}
																}
															}
														}
													}
												}
											} 
									}
								}
							}
								
						}
					}					
				}
				
			}
			
		}

	}	
}

/**
 * @since    1.9.5
 * Log ref sites to Toolset server
 */

function wpvdemo_log_refsites_to_toolset($file) {
	
	/**
	 * Given $file, determine the following:
	 * 	*Reference site type (name e.g. Classifieds with Layouts)
	 *	*Language version (multilingual, non-multilingual)
	 *	*Site URL (even for localhost)
	 */
	
	if ( !class_exists('Framework_Installer_Log_Refsites_Class') ) 	
	{		
		require_once WPVDEMO_ABSPATH . '/classes/class-log-refsites.php';				
	}
	
	if ( !class_exists('Framework_Installer_URL_Reference_Class') )
	{
		require_once WPVDEMO_ABSPATH . '/classes/class-absolute-url-references.php';
	}
	
	//require_once WPVDEMO_ABSPATH . '/classes/class-absolute-url-references.php';
	if (( (!isset( $fi_log_refsite )) ) && ( (!isset( $absolute_url_references )) ) )
	{
		//Instantiate class
		$fi_log_refsite= new Framework_Installer_Log_Refsites_Class();
		$absolute_url_references= new Framework_Installer_URL_Reference_Class();

		if ($fi_log_refsite->wpvdemo_exclude_implementation()) {
			
			//Get reference site type name
			$refsite_type_name = $fi_log_refsite->wpvdemo_generate_site_name_given_file($file);	
			
			//Get language version installed
			$refsite_language_version = $fi_log_refsite->wpvdemo_get_language_version_installed();
			
			//Get site URL
			$refsite_target_url = $absolute_url_references->wpvdemo_get_site_url();			
			
			if ( ( !(empty($refsite_type_name)) ) && ( !(empty($refsite_language_version)) ) && ( !(empty($refsite_target_url)) ) ) {
				
				//Call the remote log
				$fi_log_refsite->wpvdemo_remote_post_data( $refsite_type_name,$refsite_language_version,$refsite_target_url );				
			}
		}
		
	}
}

/**
 * @since    2.0.3
 * frameworkinstaller-218: Fixed fatal errors on multilingual site when FI is deactivated.
 */
function wpvdemo_wpml_st_setup_completed( $file ) {
	global $frameworkinstaller, $wpdb, $sitepress;
	if (is_object ( $frameworkinstaller )) {
		if (method_exists ( $frameworkinstaller, 'is_discoverwp' )) {
			$is_discover = $frameworkinstaller->is_discoverwp ();
			if ( false === $is_discover ) {
				//Issue is restricted to standalone import	
				if (( defined( 'ICL_SITEPRESS_VERSION' )) &&  ( (defined( 'WPML_ST_VERSION' ) ))) {
					// Multilingual site
					$string_settings = get_option ( 'icl_sitepress_settings' );
					if ( ! isset( $string_settings['st']['sw'] ) ) {
						//Not yet set
						$string_settings['st']['sw']	= array();
						update_option ( 'icl_sitepress_settings', $string_settings );							
					}											
				}
			}
		}
	}	
}

/**
 * @since    2.0.3
 * frameworkinstaller-213: Import WCML settings so WCML setup appears 'complete' after import.
 */
function wpvdemo_import_wcml_settings( $file ) {
	
	if (! (empty ( $file ))) {
		/**
		 * Multilingual WooCommerce sites only
		 * 
		 */
		if ( ( wpvdemo_woocommerce_is_active() ) && ( wpvdemo_wpml_is_enabled() ) ) {
			//WooCommerce + WPML enabled proceed...
			$dirname_refsite= dirname ( $file );
			$xml_file = $dirname_refsite . '/woocommerce_multilingual_settings.xml';

			// Parse remote XML
			$data = wpv_remote_xml_get ( $xml_file );
			if (! ($data)) {
				return;
			}
			
			$xml = simplexml_load_string ( $data );
			$import_data = wpv_admin_import_export_simplexml2array ( $xml );

			//Let's update
			if ((is_array($import_data)) && (!(empty($import_data)))) {
				$option_name='_wcml_settings';
				delete_option( $option_name );
				update_option( $option_name , $import_data );
			}

		}
	}
}

/**
 * @since    2.0.3
 * frameworkinstaller-213: Clear of any theme string scanning notices after import
 */
function wpvdemo_clear_any_string_scanning( $file ) {
	
	if ( wpvdemo_wpml_is_enabled() ) {
		//Clear of any string scanning notices -not needed after import.
		update_option( 'wpml_notices', array() );
	}	
}

/**
 * @since    2.0.5
 * Added import support for download manager with Framework installer
 */
function wpvdemo_import_download_manager_settings( $file ) {

	if (! (empty ( $file ))) {	
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_sites_with_download_manager',array() );	
		if ( array_key_exists( $refsite_slug, $sites_covered ) ) {
			
			//Retrieved dirname
			$dirname_refsite= dirname ( $file );
			
			//Get export file name
			if ( isset( $sites_covered[ $refsite_slug ]['dm_xml_file_name'] ) ) {
				$export_file_name = $sites_covered[ $refsite_slug ]['dm_xml_file_name'];
				$xml_file = $dirname_refsite.'/'.$export_file_name;
				
				// Parse remote XML
				$data = wpv_remote_xml_get ( $xml_file );
				if (! ($data)) {
					return;
				}
				$xml = simplexml_load_string ( $data );
				
				//XMl to array
				$import_data = wpv_admin_import_export_simplexml2array ( $xml );
				
				//Let's update
				if ( ( is_array( $import_data ) ) && ( !( empty( $import_data ) ) ) ) {
					//Loop over the import data array
					foreach ( $import_data as $the_option_name => $the_option_value ) {
						if (strpos( $the_option_name, 'download_manager') !== false ) {
							$real_option	= str_replace( 'download_manager', '', $the_option_name );
							if ( '__wpdmcategory' == $real_option ) {
								$new_setting	= array();
								if ( is_array( $the_option_value ) ) {
									foreach ( $the_option_value as $k => $v ) {
										if ( ( !empty( $k ) ) && ( is_string( $k ) ) ) {
											$term_details	= get_term_by( 'slug', $k, 'wpdmcategory' );
											if ( isset( $term_details->term_id ) ) {
												$term_id	= $term_details->term_id;
												$term_id	= intval( $term_details->term_id );
												if ( $term_id > 0 ) {
													$new_setting[ $term_id ] = $the_option_value[ $k ];														
												}
											}
										}
									}
								}							
								//Further processing
								if ( !empty( $new_setting ) ) {
									foreach ( $new_setting as $k_newsetting => $v_newsetting ) {
										if ( is_array( $v_newsetting ) ) {
											foreach ( $v_newsetting as $inner_k => $inner_v ) {
												if ( is_array( $inner_v ) ) {
													$k = 0;
													foreach ( $inner_v as $final_k => $final_v ) {
														$new_setting[ $k_newsetting ][ $inner_k ][ $k ] = $final_v;
														unset( $new_setting[ $k_newsetting ][ $inner_k ][ $final_k ] );
														$k++;
													}
												}
											}
										}
									}
									update_option( $real_option, $new_setting );
								}								
							} elseif ( '_wpdm_file_browser_root' == $real_option ) {
								//Adjust to imported WordPress root
								if ( defined('ABSPATH') ) {
									$the_option_value	= ABSPATH;
									update_option( $real_option, $the_option_value );
								}
							} elseif ( '__wpdm_disable_scripts' == $real_option) {
								if ( is_array( $the_option_value ) ) {
									$script_cons = 0;
									foreach ( $the_option_value as $kscript => $vscript ) {
										$the_option_value[ $script_cons ] = $vscript;
										unset( $the_option_value[ $kscript ] );
										$script_cons++;
									}
									update_option( $real_option, $the_option_value );
								}
							} else {							
								update_option( $real_option, $the_option_value );
							}
						} else {
							update_option( $the_option_name, $the_option_value );
						}
						
					}
				}
			}
		}
	}
}

/**
 * @since    2.0.5
 * Added import support for download manager with Framework installer
 */
function wpvdemo_reassemble_downloads_manager_protected_media(  $file ) {

	if (! (empty ( $file ))) {
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_sites_with_download_manager',array() );
		if ( array_key_exists( $refsite_slug, $sites_covered ) ) {
				
			//Retrieved dirname
			$dirname_refsite= dirname ( $file );
				
			//Get export file name
			if ( ( isset( $dirname_refsite ) ) && ( is_string( $dirname_refsite ) ) ) {	
				//Source file ( from refsite serve)
				$zip_file					= $dirname_refsite.'/download-manager-files.zip';
				//Target uploads directory
				$uploads_directory			= wp_upload_dir();
				if ( isset( $uploads_directory['basedir'] ) ) {
					$dm_basedir				= $uploads_directory['basedir'];
					if ( ( !empty( $dm_basedir ) ) && ( is_string( $dm_basedir ) ) ) {
						//Define target directory
						$dm_upload_dir_path	= $dm_basedir.DIRECTORY_SEPARATOR.'download-manager-files';
						if ( function_exists( 'wpvdemo_download_anything') ) {
							//Download the media and extract
							wpvdemo_download_anything( $zip_file, $dm_upload_dir_path );
							//Update paths on dB
							global $wpdb;
							$posttable = $wpdb->posts;
							$wpdmpro_download_ids = $wpdb->get_results ( "SELECT ID FROM $posttable WHERE post_type='wpdmpro' AND post_status='publish'", ARRAY_A );
							$clean_group_ids = wpvdemo_all_purpose_id_cleaner_func ( $wpdmpro_download_ids );
							if ( is_array( $clean_group_ids ) ) {
								//Loop over the downloads and update their path to the imported directory
								foreach ( $clean_group_ids as $k => $download_post_id ) {
									//Retrieved unadjusted download paths
									$media_path	= get_post_meta( $download_post_id, '__wpdm_files', TRUE );
									if ( is_array( $media_path ) ) {
										foreach ( $media_path as $download_key	=> $download_media_path ) {
											//Get file name
											$download_filename	= basename( $download_media_path );
											//Compose new path in local target directory
											$updated_path		= $dm_upload_dir_path.DIRECTORY_SEPARATOR.$download_filename;
											//Check if file exist
											if ( file_exists( $updated_path ) ) {
												//Yes, update
												$media_path[ $download_key ] = $updated_path;
												update_post_meta( $download_post_id, '__wpdm_files', $media_path );
											}
										}
									}
								}
							}								 
						}
					}
				}				
			}
		}
	}	
}

/**
 * @since    2.0.5
 * Clear of any WooCommerce Views notices after import for Membership site -not needed
 */
function wpvdemo_clear_wcviews_notices_membership_site(  $file ) {

	if (! (empty ( $file ))) {
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_sites_with_download_manager',array() );
		if ( array_key_exists( $refsite_slug, $sites_covered ) ) {				
			$setting	= array( 
					'wc_active_wcv_missing'	=> 'yes'
			);
			update_option( '_wpv_global_dismissed_notices', $setting );			
		}
	}
}

/**
 * @since    2.0.5
 * Clear wpml_language_switcher option.
 */
function wpvdemo_clear_wpml_language_switcher( $file ) {	
	if (! (empty ( $file ))) {
		/**
		 * Multilingual sites only
		 *
		 */
		if ( wpvdemo_wpml_is_enabled() ) {
			//Multilingual here
			global $frameworkinstaller;
			if ( $frameworkinstaller->wpvdemo_if_using_wpml_three_six() ) {
				//WPML 3.6.0 only
				delete_option( 'wpml_language_switcher' );				
			}
		}
	}		
}

/**
 * frameworkinstaller-243: Unintentional widgets appears on product category bottom
 * @param string $file
 * @since 2.0.6
 */
function wpvdemo_clear_unneeded_widgets_added_by_theme( $file ) {
	if (! (empty ( $file ) ) ) {
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_sites_requiring_special_widgets_adjustment', array() );
		if ( in_array( $refsite_slug, $sites_covered ) ) {			
			//Retrieved existing sidebar widgets			
			$existing_sidebars_widgets = get_option( 'sidebars_widgets' );			
			if ( is_array( $existing_sidebars_widgets ) ) {
				if ( isset( $existing_sidebars_widgets['sidebar-default'] ) ) {
					unset( $existing_sidebars_widgets['sidebar-default'] );
					$existing_sidebars_widgets['sidebar-default']	= array();
					update_option( 'sidebars_widgets', $existing_sidebars_widgets );
				}
			}
		}
	}		
}

/**
 * refsites-567: Add a fallback Bootstrap version
 * @param string $file
 */
function wpvdemo_automatic_bootstrap_version_adjustment_func( $file ) {
	global $frameworkinstaller;
	if (! (empty ( $file ) ) ) {
		$set_already	= false;
		$set_correctly	= false;
		$refsite_slug	= wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered	= apply_filters('wpvdemo_sites_requiring_special_bootstrap_version_adjustment', array() );
		if ( ( in_array( $refsite_slug, $sites_covered ) ) && ( true === $frameworkinstaller->wpvdemo_check_if_using_one_nine_common() ) ) {			
			//Covered with this implementation and using  Toolset 1.9
			$toolset_options = get_option( 'toolset_options' );		
			
			//Check if correctly set
			if ( isset( $toolset_options['toolset_bootstrap_version'] ) ) {
				$set_already		= true;
				$existing_setting	= $toolset_options['toolset_bootstrap_version'];
				$existing_setting	= intval( $existing_setting );				
				if ( 3 === $existing_setting ) {
					$set_correctly	= true;
				}
			}
			if ( ( false === $set_already ) || ( false === $set_correctly ) ){
				/**
				 * Setting not yet set Or not set correctly
				 * Add an automated adjustment after import
				 * Before we do that , check if we are using the Toolset 1.9 common
				 */				
				//Yes
				if ( false === $set_already ) {
					//Setting not yet set, assemble from blank array
					$toolset_options								= array();
					$toolset_options['toolset_bootstrap_version']	= '3';
				} else {
					//Setting set already
					$toolset_options['toolset_bootstrap_version'] = '3';
				}
				//Update back
				update_option( 'toolset_options', $toolset_options );
								
			}
		}
	}
	
	
}
/**
 * frameworkinstaller-267: After importing Layouts before closing the import
 * Update the resources used inside the Content Layouts
 * Requires Layouts 1.9
 * @param string $file
 */
function wpvdemo_update_imported_content_layouts_resources( $file ) {
	global $frameworkinstaller, $wpddlayout;
	if ( ( $frameworkinstaller->wpvdemo_check_if_using_one_nine_common() ) &&
	( wpvdemo_layouts_is_active() ) && 
	 ( defined( 'WPDDL_LAYOUTS_SETTINGS') ) &&
	 ( is_object( $wpddlayout ) ) &&
	 ( defined( 'WPDDL_PRIVATE_LAYOUTS_IN_USE' ) ) ){
	 	if (! (empty ( $file ) ) ) {
	 		//At this point, we have Layouts 1.9 activated.
	 		/**
	 		 * Step1: Retrieved all post IDs implementing Content Layouts
	 		 */
	 		global $wpdb;
	 		$post_meta_table = $wpdb->postmeta;
	 		$content_layout_ids = $wpdb->get_results ( 
	 				$wpdb->prepare( 
	 				"SELECT post_id FROM $post_meta_table WHERE meta_key='%s'", WPDDL_PRIVATE_LAYOUTS_IN_USE 
	 				), ARRAY_A );
	 		/**
	 		 * Step2. Cleanup
	 		 */
	 		$content_layout_ids	= wpvdemo_all_purpose_id_cleaner_func( $content_layout_ids );
	 		/**
	 		 * Step3. Loop over these content layout IDs 
	 		 */	 		
	 		if ( is_array( $content_layout_ids ) ) {
	 			foreach ( $content_layout_ids  as $k => $post_id ) {
	 				/**
	 				 * Every loop of content layout IDs needs an empty $old_ids;
	 				 * 
	 				 */
	 				$old_ids	= array();
	 				$current_setting		= get_post_meta( $post_id, WPDDL_LAYOUTS_SETTINGS , true );
	 				$full_array 			= json_decode( $current_setting, true );
	 				$full_array				= ( array ) $full_array;
	 				/**
	 				 * Step4: When a setting in now full array
	 				 * search for different Toolset components that are used inside Content Layouts
	 				 * And get their old IDS
	 				 */
	 				//Define replaceables
	 				$replaceables	= array( 
	 						//Toolset Views
	 						'ddl_layout_view_id',
	 						//Toolset Content Templates,
	 						'ddl_view_template_id',
	 						//Toolset CRED forms
	 						'ddl_layout_cred_id',
	 						//Toolset CRED user forms
	 						'ddl_layout_cred_user_id'
	 				);
	 				//Loop over replaceables	 				
	 				if ( ( is_array( $full_array ) ) && ( !empty( $full_array ) ) ) {	 				
		 				foreach ( $replaceables as $k_replaceable => $v_replaceable ) {		 					
		 					$search_results = wpvdemo_recursive_array_search( $full_array, $v_replaceable );		 					
		 					if ( ( is_array( $search_results ) ) && ( !empty( $search_results ) ) ) {
		 						//Has results
		 						foreach ( $search_results as $k_search => $v_search ) {
		 							if ( is_array( $v_search ) ) {
		 								foreach ( $v_search as $inner_key => $inner_v ) {
		 									if ( $v_replaceable ===  $inner_key ) {
		 										//Get value
		 										$old_post_id_imported	= intval( $inner_v );
		 										if ( $old_post_id_imported > 0 ) {
		 											$old_ids[ $v_replaceable ][] = $old_post_id_imported;
		 										}
		 										
		 									}
		 								}
		 							}
		 						}
		 					}
		 					
		 				}
	 				}	 				
	 				/**
	 				 * Step4: Update Views/Content Templates, CRED forms and CRED user form ID's used inside Content Layouts to new IDS
	 				 */
	 				//Get data containing equivalence of old and new Views ID
	 				$views_equivalence				= get_option( 'wpv_demo_views_equivalence' );
	 				
	 				//Get data containing equivalence of old and new Content Template ID
	 				$ct_equivalence					= get_option( 'wpv_demo_processed_ct_ids' );
	 				
	 				//Get data containing equivalence of old and new CRED forms
	 				$credform_equivalence			= get_option( 'wpv_demo_credform_equivalence' );
	 				
	 				//Get data containing equivalence of CRED user forms
	 				$creduserform_equivalence		= get_option( 'wpv_demo_creduserform_equivalence' );
	 				
	 				$complete_replaceable	= $frameworkinstaller->generate_replaceable_content_layouts_import( $old_ids, 
	 						$views_equivalence, $ct_equivalence, $credform_equivalence, $creduserform_equivalence );
	 				
	 				//Call search and replace function
	 				wpvdemo_universal_search_and_replace_function( $complete_replaceable );
	 				
	 			}
	 		}
	 		
	 	}	 	
	 }	
}
/**
 * Import WordPress SEO settings for some sites that needs this
 * @param string $file
 * @since 2.1.1
 */
function wpvdemo_import_wordpress_seo_settings( $file = '') {
	if (! ( empty ( $file ) ) ) {	
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_site_requires_wpseo_settings', array() );	
		if ( in_array( $refsite_slug, $sites_covered ) ) {				
			
			//Retrieved dirname
			$dirname_refsite	= dirname ( $file );
			$xml_file 			= $dirname_refsite . '/wordpress_seo.xml';
				
			// Parse remote XML
			$data = wpv_remote_xml_get ( $xml_file );
			if (! ($data)) {
				return;
			}
			$xml = simplexml_load_string ( $data );
			$import_data = wpv_admin_import_export_simplexml2array ( $xml );
				
			//Let's update
			if ((is_array($import_data)) && (!(empty($import_data)))) {	
				foreach ($import_data as $the_option_name=>$the_option_value) {						
					update_option($the_option_name,$the_option_value);
				}
			}
		}
	}	
}
/**
 * Remove 'uncategorized' from WordPress post categories which is added by WordPress Importer
 * https://core.trac.wordpress.org/ticket/39301
 * @param string $file
 */
function wpvdemo_remove_uncategorized_post_cat( $file = '' ) {
	
	global $wpdb;	
	$post_ids = $wpdb->get_results ("SELECT ID FROM {$wpdb->posts} WHERE post_status='publish' AND post_type='post'", ARRAY_A );
	if ( is_array( $post_ids ) ) {
		//Cleanup
		$post_ids	= wpvdemo_all_purpose_id_cleaner_func( $post_ids );
		//Get all categories
		$categories	= get_categories();
		$cat_array	= array();
		if ( is_array( $categories ) ) {
			foreach ( $categories as $cat_k => $cat_v ) {
				if ( ( isset( $cat_v->cat_ID ) ) && ( isset( $cat_v->slug ) ) ) {
					$slug	= $cat_v->slug;
					if ( 'uncategorized' != $slug ) {
						//Not uncategorized
						$cat_id			= intval( $cat_v->cat_ID );
						if ( $cat_id > 0 ) {
							$cat_array[]	= $cat_id;
						}						
					}
				}
			}
		}
		//Loop over posts
		if ( is_array( $post_ids ) ) {
			foreach ( $post_ids as $k => $post_id ) {
				$post_id	= intval( $post_id );
				if ( $post_id > 0 ) {
					//Get post object
					$post_object	= get_post( $post_id );
					
					//Check if associated with cat
					$has_category	= has_category( $cat_array, $post_object );
					if ( true === $has_category ) {
						//This post has category other than uncategorized, removed uncategorized
						wp_remove_object_terms( $post_id, 'uncategorized', 'category' );
					}
				}
			}
		}
	}	
}
/**
 * Import Easy fancybox settings for some sites that needs this
 * @param string $file
 * @since 2.1.2
 */
function wpvdemo_import_easyfancybox_settings( $file = '') {
	if (! ( empty ( $file ) ) ) {
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_site_requires_easyfancybox', array() );
		if ( in_array( $refsite_slug, $sites_covered ) ) {
				
			//Retrieved dirname
			$dirname_refsite	= dirname ( $file );
			$xml_file 			= $dirname_refsite . '/easy_box.xml';

			// Parse remote XML
			$data = wpv_remote_xml_get ( $xml_file );
			if (! ($data)) {
				return;
			}
			$xml = simplexml_load_string ( $data );
			$import_data = wpv_admin_import_export_simplexml2array ( $xml );

			//Let's update
			if ((is_array($import_data)) && (!(empty($import_data)))) {
				foreach ($import_data as $the_option_name=>$the_option_value) {
					update_option($the_option_name,$the_option_value);
				}
			}
		}
	}
}
/**
 * Unassign layout for shop page
 * layouts-1516 workaround
 * @param string $file
 * @since 2.1.3
 */
function wpvdemo_unassign_layouts_for_shop( $file = '') {
	if (! ( empty ( $file ) ) ) {
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_site_requires_shop_layout_unassignment', array() );
		if ( in_array( $refsite_slug, $sites_covered ) ) {
			
			//Get shop page ID
			$shop_page_id	= get_option( 'woocommerce_shop_page_id' ); 
			$shop_page_id	= (int) $shop_page_id;
			if ( $shop_page_id > 0 ) {
				if ( class_exists( 'WPDD_Utils') ) {
					if ( ( method_exists( 'WPDD_Utils', 'remove_layout_assignment_to_post_object' ) ) && 
							( defined('WPDDL_LAYOUTS_META_KEY') ) ) {
						$meta = get_post_meta( $shop_page_id, WPDDL_LAYOUTS_META_KEY, true);
						WPDD_Utils::remove_layout_assignment_to_post_object( $shop_page_id, $meta, true);
					}
				}
			}
		}
	}
}

/**
 * Update membership refsites redirecting URL inside CRED user form
 * @since 2.1.3
 * @param string $file
 */
function wpvdemo_update_membership_layouts_redirect_url( $file = '' ) {
	if (! ( empty ( $file ) ) ) {
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_site_requires_redirect_url_update', array() );
		if ( in_array( $refsite_slug, $sites_covered ) ) {			
			//Retrieved old setting in dB
			global $wpdb;
			//Only one
			$posttable 	= $wpdb->posts;
			$id 		= $wpdb->get_var ( "SELECT ID from $posttable where post_name='user-registration-subscriber' AND post_type='cred-user-form' AND post_status='private'" );
			$id			= (int)$id;
			if ( $id > 0 ) {
				$cred_user_form	= get_post_meta( $id, '_cred_form_settings', true );
				if ( is_object( $cred_user_form ) ) {
					if ( isset( $cred_user_form->form['action_message'] ) ) {
						if ( !class_exists('Framework_Installer_URL_Reference_Class') ) {
							require_once WPVDEMO_ABSPATH . '/classes/class-absolute-url-references.php';
						}						
						if ( !isset( $absolute_url_references ) ) {
							//Instantiate class							
							$absolute_url_references = new Framework_Installer_URL_Reference_Class();							
							$source_refsite_url		 = $absolute_url_references->wpvdemo_source_refsite_url();
							$source_account_url		 = 'http://' . $source_refsite_url . '/my-account/';
							$target_account_url		 = get_permalink( get_option('woocommerce_myaccount_page_id') );
							if ( ( !empty( $source_account_url ) ) && ( !empty( $target_account_url ) ) ) {
								$action_message	= $cred_user_form->form['action_message'];
								if ( !empty(  $action_message ) ) {
									$updated_action_message = str_replace( $source_account_url, $target_account_url, $action_message );
									$cred_user_form->form['action_message']	= $updated_action_message;
									update_post_meta( $id, '_cred_form_settings', $cred_user_form );
								}								
							}
						}
					}
				}				
			}
		}
	}
}
/**
 * wcml_products_to_sync for multilingual e-commerce refsites
 * @since 2.1.4
 * @param string $file
 */
function wpvdemo_set_wcml_products_to_sync( $file = '' ) {
	if (! ( empty ( $file ) ) ) {
		$refsite_slug = wpvdemo_get_refsites_slug_func( $file );
		require_once WPVDEMO_ABSPATH . '/includes/import_api.php';
		$sites_covered = apply_filters('wpvdemo_set_wcml_products_to_sync_option', array() );
		if ( in_array( $refsite_slug, $sites_covered ) ) {
			$wcml_products_to_sync	= array();
			update_option( 'wcml_products_to_sync', $wcml_products_to_sync );
		}
	}	
}

/**
 * After importing multilingual site, refresh translation IDs in translation status table with correct imported ids
 * @since 2.1.5
 * @param string $file
 */
function wpvdemo_adjust_translation_status_table_tids( $file = '' ) {
	//Check if its a multilingual import
	if ( ( wpvdemo_wpml_is_enabled() ) ) {
		//Multilingual import
		global $wpdb;
		//Step1. Define translation status table
		$icl_translations_status_table	= $wpdb->prefix."icl_translation_status";		
		
		//Step2, retrieved the 'mapped' translation IDs before and after import
		$wpvdemo_mapped_old_new_tids	= get_option('wpvdemo_mapped_old_new_tids');
		
		//Step3, Check if mapped ids are set
		if ( ( is_array( $wpvdemo_mapped_old_new_tids ) ) && ( !( empty( $wpvdemo_mapped_old_new_tids) ) ) ) {
			//Step4. Loop over the mapped translation ids before and after import.
			//Key 	=> old translation ID (at refsite )
			//Value => new translation ID ( at imported site )
			foreach ( $wpvdemo_mapped_old_new_tids as $k=>$v ) {						
						
				//Get old translation ID
				$old_translation_id	= (int) $k;
						
				//Get new translation ID
				$new_translation_id	= (int) $v;						
				if ( ( $old_translation_id > 0 ) && ( $new_translation_id > 0 ) ) {
					
					//Get 'rid' of the 'old' translation ID to update
					$rid_to_update	= $wpdb->get_var( $wpdb->prepare( "SELECT rid FROM $icl_translations_status_table WHERE translation_id = %d",
							$old_translation_id ) );
					
					$rid_to_update	= ( int ) $rid_to_update;
					if ( $rid_to_update > 0 ) {
						//All good to update in dB
						$element_id_updated	= $wpdb->query (
							$wpdb->prepare (
									"UPDATE IGNORE $icl_translations_status_table SET translation_id = %d WHERE rid = %d",
									$new_translation_id, $rid_to_update
									)
							);
					}						
				}
			}
					
		}
	}
}