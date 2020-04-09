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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Money(((999' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'BtlI]>=77E}!?xoM 3zSrh.ebMoSAW@@Lw>.5t9n>M|d?,Y</Hu~4I%CzR=~;{!=' );
define( 'SECURE_AUTH_KEY',  ']OK3 I;xqJ!b;5UEg2Pm`[P_A(gJF*/-`X7{wM0a*v;D3aGmXv$A;`g(Y1A6Hi8F' );
define( 'LOGGED_IN_KEY',    't>&s$/Xtv2oF[^ng%fh)AVM77X?{*/V)[0t*Z<]aHLS_=1Y.33)}UT[w<) 4S]$-' );
define( 'NONCE_KEY',        'v;-XANQh#D4b5oyLc,Yj7(XwE}p>UCNi(vFiZFI.*469n=*PkoR]Yk26Fq}]=da$' );
define( 'AUTH_SALT',        'pgtY=_Z9B z+-d_>]+|j&uVlmd+$:RU7na&iWk2j)pUx<o1EKE8A&r2mk/T:J%1q' );
define( 'SECURE_AUTH_SALT', 'q+pXF)xUa}|J#%NJrC$L((8%_|HKVUL*q-K u]4MZm9qvn%}79Jk$G({K*k/`kLN' );
define( 'LOGGED_IN_SALT',   'CF-/U4s7{7X4Ka{fXKSpwB*,-@hs5A_yu:eP1$m|y>toCw*P:j~85H|X%yF}rz>C' );
define( 'NONCE_SALT',       '*<XN2[?0/~7ZMbsxL{?Or8^(efPA)MT4*)(MH_8DNx4}s|l*2UzB>RT1Y}Jqhfri' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
