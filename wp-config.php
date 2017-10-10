<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ts_';

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'starter_wp_com_2017');
define('DB_USER', 'starter');
define('DB_PASSWORD', 'VfLyb35EsmstxKLy');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_general_ci');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'WF&AorjL~wk@xssPG-8|F#m7^7Y&j|7nY5)%,#f|+FQS<]?M]XEl3i?IXelu?Me8');
define('SECURE_AUTH_KEY',  'E:cex10?LHY;OO#cb(}uEZ4e-l6wM;d/F}^WHSPqJl)TnS&<+);l5oN!`E_NUGgS');
define('LOGGED_IN_KEY',    's>a,pH9sVh.uDB>y]JDz-{FAA+E}.Du9tQ]RWd[6c6HGz}$}9Qa`Nnyr8nZb3DM?');
define('NONCE_KEY',        'W!*8 hoU-,7PH*?>E#C<^4!J}tF0@tU0MUh[GC 8BU7}.gmzD$oC|thJ<P;yR&U.');
define('AUTH_SALT',        'd23BdYzkcF%I@k;oQI>OeHT}c~!FSi2+-Tw&b}V>MJnm$w&AmM.]Q/C;L^O<~.G[');
define('SECURE_AUTH_SALT', '4)#s]rK;#mn8O>JvR$(<|MU^(juDYHdM*xw0bIvj/?;E{x.wJv3P#ulg #~mGLmZ');
define('LOGGED_IN_SALT',   'Z-6_St2KofGvEW0m~cM;cucKAt2kn!`<-NQz^.3LQI-X)3c_?nAcEs( `r,`a(TD');
define('NONCE_SALT',       '91_~s5Jx2o[PI7{[`ymL<$ 2~LZ(0%=f#~ `T9%,JN:l^=i[{8]s[&=x,(9]2B+m');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */

if ( isset($_SERVER['APPLICATION_ENV']) ) {
    $environment = $_SERVER['APPLICATION_ENV'];
}
if ( ! isset($environment) ) {
    $environment = (stripos($_SERVER['HTTP_HOST'],'razvija.se') !== false) ? 'development' : 'production';
}

if ($environment === 'development') {
    
    ini_set('xdebug.var_display_max_depth', 20);
    ini_set('xdebug.var_display_max_children', 256);
    ini_set('xdebug.var_display_max_data', 5000);

    define('WP_HOME', 'http://starter.razvija.se/');
	define('WP_SITEURL', 'http://starter.razvija.se/');

	define('BLOG_PUBLIC', false);

    define('FS_METHOD', 'direct');

    define('WP_DEBUG', false);
    define('WP_CACHE', false);

} else {
    define('WP_HOME', 'http://starter.razvija.se/');
	define('WP_SITEURL', 'http://starter.razvija.se/');

	define('BLOG_PUBLIC', true);

    define('FS_METHOD', 'ssh2');
    define('FTP_PUBKEY','/home/emisftp/emi_rsa.pub');
    define('FTP_PRIKEY','/home/emisftp/emi_rsa');
    define('FTP_USER','emisftp');
    define('FTP_PASS','');
    define('FTP_HOST','127.0.0.1:22');

    define('WP_DEBUG', false);
    define('WP_CACHE', true);

}

define('FTP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'].'/wp-content/');
define('FTP_PLUGIN_DIR ', $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/');
define('DISALLOW_FILE_EDIT', true);
define('WP_AUTO_UPDATE_CORE', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

?>