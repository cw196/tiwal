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
define('DB_NAME', 'tiwa2863280289');

/** MySQL database username */
define('DB_USER', 'tiwa2863280289');

/** MySQL database password */
define('DB_PASSWORD', 'uJI1*gtj@wN');

/** MySQL hostname */
define('DB_HOST', 'tiwa2863280289.db.2863280.hostedresource.com:3307');

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
define('AUTH_KEY',         'S<mQBr|n ?0Fo<tYX3b<5%5J9cf%Fb$r[!2o)H]Lt-qk|Z%>S0!p4Et$6]|Ee<@K');
define('SECURE_AUTH_KEY',  ':6yt<6iE%S!:lj?)J@[{.UP9tAT~r{c3Q-M{:@aF[m?S+rRG_8=3yQV{77hJy-jz');
define('LOGGED_IN_KEY',    '7S+m-+])eSF9e:)BZ-{`f38IiA8PZ+g{/|hA%mA2)[CvVbPaP~?M,:;J3]*&XpAO');
define('NONCE_KEY',        '(v+JO$I8;Wn,~-aJ)H?fCrhm5Z:+]!A4Oq+4m.xAQRbs(Ke|)8-U>x9M=hZ_NuQA');
define('AUTH_SALT',        '-M2St!jiSxc[:6 E|+RO.5c]Ny:|EF0ooz?p5)Xwt?bm*qS0$:|2lHn5[f;yh[?W');
define('SECURE_AUTH_SALT', 'cB{iB(15~e3:]D& -,=lTS#.A#9y]Kc,m]!)%~Am&JR`htk2?z5# qX 8A/NEaZQ');
define('LOGGED_IN_SALT',   'DGB=c1=NRbA|F$y@V-Gv0V|vRRNFhC%X{CFLF&I}5|dE; ?V._,Gxm8=FhM<!{i)');
define('NONCE_SALT',       '&%UQRpM<+21j%L/GTshHHKhYW;sG-[=Wy8W.{;f9vBx!/9l-fu8|U;Jd)`6PyiBx');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'zh_';

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
