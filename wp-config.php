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
define('DB_NAME', 'zanbil-v1');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '|dc3ZNRO3lAP;[|.hI9[>(-=LJ,yxzr%y$*qsV+3OHPW?gn] 1O}.EANAp:@CN)q');
define('SECURE_AUTH_KEY',  ',yR&|f/!)bsj4-!1+>Ue$z@b-C:RAxy{J^@%r##$jLW;SX5:Kh6uuNbz.eiL6Q;R');
define('LOGGED_IN_KEY',    'cdEZ?-rJY;_V*cHF(xOgm;}Sabs`>^jn{qKflw%YogL:Z:hiXex130Bh!,dsrKu~');
define('NONCE_KEY',        '_?%sZpG,7B/rkaG)}%u%=$K6LjfPefMT462kI^ZYrf<{Q7IiF=X;#pSC:,:vwyu}');
define('AUTH_SALT',        'bTN%L#6:R0q{3K=[<j]Nas$fP6H@(!!:}-,-_Y9es)HuKpCO/Egk=BLW);KCB}Sq');
define('SECURE_AUTH_SALT', ',{* -V{A!OjnG$Qu (E=hIloY3?j`mrC<b2uoCfh6N*7AHs-0=JPk4oZ:qeQTA09');
define('LOGGED_IN_SALT',   'eqz1l>=`*HD%ly0W@{YrlR6FeiW@xh0{e|^9=/!,+RE*JV&<[We5h$xq_m*_S^lJ');
define('NONCE_SALT',       'r}CWMOaf~D`kB.GnF9/^JhgXi$RQXZ9|shNGJ#0=]T/A7EPs6 |6l8rslme%2RPT');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'avn_';

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
