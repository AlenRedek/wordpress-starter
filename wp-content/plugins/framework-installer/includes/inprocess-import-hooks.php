<?php
/** IN-PROCESS IMPORT HOOKS HERE
 *  FUNCTIONS HOOKED HERE ARE FOUND IN /includes/main_functions/inprocess-import-functions.php
 *  THESE HOOKS RUNS WHILE FRAMEWORK INSTALLER IS PROCESSING THE IMPORT
 *  do_action('wpvdemo_import_before_step_' . $step, $settings);
 *  do_action('wpvdemo_import_after_step_' . $step, $settings);
 *
 *  - START
 */

/** Membership site compatibility */
add_action('wpvdemo_import_after_step_2', 'wpvdemo_create_download_history_table', 10, 1 );

/** frameworkinstaller-254: */
add_filter( 'wpml_st_get_post_string_packages', 'wpvdemo_set_empty_string_packages_inprocess_import', 10, 2 );

/** frameworkinstaller-308: Reimport Access in some cases where its Layouts dependent */
add_action('wpvdemo_import_after_step_11', 'wpvdemo_reimport_types_access', 10, 1 );

/** IN-PROCESS IMPORT HOOKS HERE
 *
 * - END
 */