<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'site_meg');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 's1t3-m3g');

/** MySQL hostname */
define('DB_HOST', 'site-meg.chnpbugrdpde.us-west-2.rds.amazonaws.com');

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
define('AUTH_KEY',         'u2#BJEfi+M;|zUcCBj`<N-|f}z3];5_;^S,_FGZXuS;E?|h}mCHq*B`6p~&jd.GF');
define('SECURE_AUTH_KEY',  'MNUFPkx&n8&aObNPLo.1j%}?@_+eYiglTKj?s0f!4N;6*4GFYndWo-L2Axh&`a)=');
define('LOGGED_IN_KEY',    '?MI~*qe3g Q3,:c+Ny/;vzq4l5.n)j2T-e+;50(A2n4udLg:&*qn#SJ1)K[JTnN(');
define('NONCE_KEY',        'XZ%Ob!2f{<cH2qf?|@AhlszQ5eY6hZHK8C:FJp>[Nn{U6T.M%8MRX}+3A@DiO7!p');
define('AUTH_SALT',        'gIz,nAa1e*ULxK&_<0lL?y@nt[j-;+0jEoK%e}k+j8WkPVR^P{fwX|tw-4+R|zd$');
define('SECURE_AUTH_SALT', 'c2Teado|5L(s->nK9UR,x_H:+,p@]VsKwS96,ONKrCOy8ml:E+E,;jkat`aF^69|');
define('LOGGED_IN_SALT',   'wK/QD|nYM$XK+]11LK^m3rRXvL,A~sNjily&%WV*.<YUg]&tH&2^q!;cJp}Cg^66');
define('NONCE_SALT',       'Wp1yUD/-yZL}^zZU~lnyw1}waM%;+(F+G5 T_AS<%qe|0Z(gTU5-Cq:[^Rh*1sid');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'site_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
