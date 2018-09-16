<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/includes/init.php';

/**
 * Initialize theme default settings
 */
require get_template_directory() . '/includes/admin/theme-settings.php';

/**
 * Load Editor functions.
 */
require get_template_directory() . '/includes/admin/editor.php';

/**
 * Custom Header Image.
 */
require get_template_directory() . '/includes/admin/custom-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/admin/customizer.php';

/**
 * Check for theme required plugins.
 */
require get_template_directory() . '/includes/plugins/required-plugins.php';

/**
 * Register widget area.
 */
require get_template_directory() . '/includes/widgets/widgets.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/frontend/template-tags.php';

/**
 * Custom pagination for this theme.
 */
require get_template_directory() . '/includes/frontend/pagination.php';

/**
 * Custom Comments file.
 */
require get_template_directory() . '/includes/frontend/custom-comments.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/includes/frontend/bootstrap-wp-navwalker.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/includes/frontend/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/includes/utilities/jetpack.php';

/**
 * Load WooCommerce functions.
 */
require get_template_directory() . '/includes/utilities/woocommerce.php';
