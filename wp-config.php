<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'WEgveTZgV3MjfCB5fFo+dTp1OVRDUVFwSXpMWi4+LD9Wc31GaGNSbXlTPz4kJnJzeT1iNVRfOVR9bTsxUTo0SA==' );

define( 'WP_CACHE', true ); // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tsue' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '$c!bu#TSAcyeti+Cg&g]Wc:A9PG;(Pa$c3MQB^s:7gvS#&7KV0;Eyd|/d`p!Al9<' );
define( 'SECURE_AUTH_KEY',  'y|?y48Lpr0n~tQ/07S 3nhvxS.`EsRQ2CMJjY/NaJ^/Rptsy~br#OQ[KjMceD{(B' );
define( 'LOGGED_IN_KEY',    's14`8Ei!@joI.6Gq@Q,d64z~D!(JX^TSyb[K}e,uo{9_zW:96B4O#aFqBi|/e.$B' );
define( 'NONCE_KEY',        'xruwh1E&iBE?I)Xo^sEYk,Xf)MYC<p[=p`^AMW-I_V**,%xlU!|S.NEPeK`dg:><' );
define( 'AUTH_SALT',        'hsfb[hzbr.^|Q_r`!Yr|ka< O7o,y:_P+za0r%gl(@cMx;:FT T@?U*<$joU6aLU' );
define( 'SECURE_AUTH_SALT', 'z~9-DR,<V.yxW:6AVh,jCUvX[|]gzACHqakzMh49+,46R/[#5JE{!Vungc<X-N@~' );
define( 'LOGGED_IN_SALT',   '8}Q-CHAR/i1eM=2e1c^0+hX$U$nEb #Kl9KQAbZ_^}O:Yo4w!z 36C%<3-/@1 eD' );
define( 'NONCE_SALT',       'BT]=xEBMiMcWIw8~16hr6ITGRp%H&bGew>;69Bbrr$j54kp.DJwa* MJzP,JZ8t>' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
