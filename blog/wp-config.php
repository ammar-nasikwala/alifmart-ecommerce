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
define('DB_NAME', 'alif_wp2');

/** MySQL database username */
define('DB_USER', 'alif_wp2');

/** MySQL database password */
define('DB_PASSWORD', 'V#blaEqk*(qzKC~4yD.87.*0');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         '13gfnAPvxgkk3ECXzWMTZZca3R5AVPkI7HrJdowPQTRS8Zdo127cW4jKXjK7cSLl');
define('SECURE_AUTH_KEY',  'FMgKZHe9AGqVMVAsoZaYFJN9vjXKxlBS9GlfVGpbkg6dVTu9Z2AMNRdyPCvRJCl9');
define('LOGGED_IN_KEY',    'mzZ4f1A5MOCi9OKj4ZeDbYSMlRZG0MJgDf5i1RUQLuXCIq2OF2FDYcQ2P70Whfkk');
define('NONCE_KEY',        'GU5kOKwHCWj33rGwIxCOjpuaabm9LdToAY3z1XvaMIMRMPac4OJdjYNbK1Vovmkc');
define('AUTH_SALT',        'SPwDbXXG84g8adw8b0clnJEHWugy70J2qNBIjjCvr3MsDCHHuYjdn3EYE5gxsLTl');
define('SECURE_AUTH_SALT', 'SJ1XPyNrUh3wQ0mnGF52SvrkxEV3TXAzVotLe6wiXi3lqk9H0RenkI1D1qqAtx1U');
define('LOGGED_IN_SALT',   'VG02mj0QQSlypUfXrTyqKnWXfrcYvYYoR62BnCTIOjiszQ5fqbLiBJ7fC4jjHgI9');
define('NONCE_SALT',       'tj1EtERQ9KxJbM7mKaHDXhGEzlp7MDV0K8VzfA7Ff1DCc3GaFfllf7zNpWOac2Hk');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');define('FS_CHMOD_DIR',0755);define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');

/**
 * Turn off automatic updates since these are managed upstream.
 */
define('AUTOMATIC_UPDATER_DISABLED', true);


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
