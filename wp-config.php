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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'gantil_mrsd');

/** MySQL database username */
define('DB_USER', 'gantil_mysql');

/** MySQL database password */
define('DB_PASSWORD', 'icbi0dws');

/** MySQL hostname */
define('DB_HOST', 'gantil.mysql');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@Z{q|W<A k3cD*+zK.2exVqrT%F;v(UIDB:Wl(84GtD#I2YYe3+`#q10fkbk|j6z');
define('SECURE_AUTH_KEY',  'c;u~P{[RS[,:n8PYW))u#?:0AvOxm/}iO#|/i+cL?/GVxgyNZwK_!,}*](w$>M r');
define('LOGGED_IN_KEY',    'xc)]b9canz_b_WOk+co-a(tT9?bzG*d^=-_%Jp|^2JEjU=*8AWm;!~+?m}-Itb[H');
define('NONCE_KEY',        '2L`w[+C+$Mk1IJp$O}J;t;oKP<yYUf~!MqX7]YY95g~jx9c8WyN6ObG=+Nf]K|1f');
define('AUTH_SALT',        ';S1x3!RM<00l;R8NK1.un$BROC0f4p|w2X~kGtAnk3ZrM,w|~b<`d%>Ie(<OG#!k');
define('SECURE_AUTH_SALT', 'y@`E7K8jpd)br5kU]BxC37dWpPzfJ,Ptj$<g[Th8]le_ui4o`o#FZznoGqDQBPHg');
define('LOGGED_IN_SALT',   'v=durnyLM&paW-dbR9HBd,J-v}4hyY|wl?61r^XfAe9sb-A5CsSt}xEg:pet^7U%');
define('NONCE_SALT',       '#{`U89TUcP+xv#C[@/Dd:GKb/lEhYVEr^_z``E(/kK@BeTv5C%gz@/f.l@q0ipn$');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'mrsd_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
