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
define( 'DB_NAME', 'hdworld' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',         'ppKTv29D.xkrvA8n/}E@IZ,=Py!R7OX1(KC}|M2cgO*a:29Af^>1_2/O1 AEEeq]' );
define( 'SECURE_AUTH_KEY',  '[Q^<9W]?U|[Sm`mid[l{j3^q&svrr?U,3O-H/1nJbN>s8DpCUMy,_z(WR4M|XJ$R' );
define( 'LOGGED_IN_KEY',    'S`L|abfs4/1_v6WU7oa+X]eL on/;;^aM*+1C<%3bz4Z+1I#O_Re0<xazFf@<5Z}' );
define( 'NONCE_KEY',        '4SIir_Yr}@?$):;!UKP8Qsm+YDGX&:yCmTyyLG).Ez,]?3u]34G`cZm[(%KV+!v)' );
define( 'AUTH_SALT',        'sg9qtQ^OO^~<][5?%K&&IR*jT9^RTb,p0#*R}lUuSQ2A{/>f68cq_=0mbI{E_pD,' );
define( 'SECURE_AUTH_SALT', 'qJabY7_+g#,Y![H@RKuWn&xw^oWGx$jWB|2(0PMDWNK0h^$>7%~IHSkf4k=dcDT&' );
define( 'LOGGED_IN_SALT',   '&<}}OsTx q~gJM<4Et6p6N>9~FX<2##;-p&axM?ypu3z&fwao(htoYJYRiZ,(kQ1' );
define( 'NONCE_SALT',       '`/?A eTI}RvX2Ns|FZR?B*)e#Jg8.+{|x35@q?0aL-HI,fk$:#*spNp#PbW$r}wR' );

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
