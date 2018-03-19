<?php

/*******************************************************
*
*	Plugin Name: 	Purgatorio
*   Author: 		Alen Redek
*   Author URI: 	https://www.redek.me/
*   Description: 	Various helper functions and classes for faster theme development
*   Version: 		1.0
*   License:        GNU General Public License v2 or later
*   License URI:    http://www.gnu.org/licenses/gpl-2.0.html
*	Text Domain:	purgatorio
*
*******************************************************/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2016 Alen Redek
*/

define( 'PURGATORIO_VERSION', '1.0' );
define( 'PURGATORIO__PLUGIN_FILE', __FILE__ );
define( 'PURGATORIO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PURGATORIO__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PURGATORIO__PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'PURGATORIO__SETTINGS', 'purgatorio_settings' );

require_once(PURGATORIO__PLUGIN_DIR.'purgatorio-class.php');
require_once(PURGATORIO__PLUGIN_DIR.'includes/global-functions.php');
require_once(PURGATORIO__PLUGIN_DIR.'includes/global-init.php');
require_once(PURGATORIO__PLUGIN_DIR.'includes/i18n.php');
require_once(PURGATORIO__PLUGIN_DIR.'includes/security.php');
require_once(PURGATORIO__PLUGIN_DIR.'includes/tracking.php');
require_once(PURGATORIO__PLUGIN_DIR.'includes/seo.php');
//require_once(PURGATORIO__PLUGIN_DIR.'widgets/widgets.php');
//require_once(PURGATORIO__PLUGIN_DIR.'shortcodes/shortcodes.php');

/* Query class */
require_once(PURGATORIO__PLUGIN_DIR.'includes/class-queries.php');
global $pg_query;
$pg_query = PG_Queries_Class::getInstance();

/* Attachments class */
require_once(PURGATORIO__PLUGIN_DIR.'includes/class-attachments.php');
global $pg_attachments;
$pg_attachments = new PG_Attachments_Class();

/* Google Maps class */
require_once(PURGATORIO__PLUGIN_DIR.'gmaps/class-gmaps.php');
global $pg_gmaps;
$pg_gmaps = new PG_GMaps_Class();

if ( is_admin() ) {
	require_once( PURGATORIO__PLUGIN_DIR . 'purgatorio-admin.php' );
	Purgatorio_Admin::init();
}

register_activation_hook( __FILE__, array( 'Purgatorio', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Purgatorio', 'plugin_deactivation' ) );
add_action( 'init', array( 'Purgatorio', 'init' ) );
add_action( 'plugins_loaded', array( 'Purgatorio', 'plugin_textdomain' ), 99 );

Purgatorio::init();

?>