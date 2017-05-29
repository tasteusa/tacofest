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
define('DB_NAME', 'fest');

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


define('FS_METHOD','direct'); 

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '<DD^_+$^fVM;>Xq5l23O[}oxGYkiG2clnDqZQ:2D.PE2l`I% j(+P`|D;>0o6n0X');
define('SECURE_AUTH_KEY',  '(Cq9UK,Le_RO8PAGTXFo/z=pKWQG,^/8>qn!7`<>:~X8;%R&IT`EUp5*BgX`(|eg');
define('LOGGED_IN_KEY',    'o-mK?leUT3,eid4-sX#:YMHTKg/vQ.n8B?7Ix4j!]H2Ou~<luq-hb|+na9W-&-fR');
define('NONCE_KEY',        'L+o)cu<]A32bre>{Fz~<BTMuxx5Q-/4M(ftz>w7=e/ak-Rfq.fzanF}BDU8W6`8A');
define('AUTH_SALT',        'NYX?z{,($[Nv|y{h=!d^In7~KF(2U|7x=BSX#n#~G3rJ0?d9o`DovhDMW0+2aT#a');
define('SECURE_AUTH_SALT', 'jOxGHQAeSD.h9Z0M+(5.y/z`%o^0MKTd!enz%U(K38[ 0ARw^:I@uFpLBuKaNua5');
define('LOGGED_IN_SALT',   '`=Qn$1S|Y+mA!Bm mh1|3a5PrB[SRqiLy#ClX@51m=}_yvzAg<[gMMj!q`$ R>Hq');
define('NONCE_SALT',       'e`Qu*LS?{jq$oz*3ww@/^)l1zbi&54{W5~hA%0{#d!R$[Pk~%X&{k&jY*MTa6`j@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
