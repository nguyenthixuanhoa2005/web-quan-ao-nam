<?php
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
define( 'DB_NAME', 'if0_41768496_wp653' );
/** Database username */
define( 'DB_USER', 'if0_41768496' );
/** Database password */
define( 'DB_PASSWORD', 'Xuanhoa2412' );
/** Database hostname */
define( 'DB_HOST', 'sql100.infinityfree.com');
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/** Tắt cái gọi đến cái này để cập nhật theme*/
define('DISABLE_WP_CRON', true);
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
define( 'AUTH_KEY',         'zi3g4n6fnwzpagtwupdohizsekc14tusvy3n9wmzrra9koqky1ghh0asm3dobuxi' );
define( 'SECURE_AUTH_KEY',  'xdzgghbteteqskkudc30aof3dzrxixfou2pccubh44cwodo1iu5pkmbpusozarb7' );
define( 'LOGGED_IN_KEY',    'hvcamt5puclqmc1q1ok55nyq2zh0zangl1npkwadbpztdhb9wg1bq3rnkp7aclql' );
define( 'NONCE_KEY',        '6f86fnitglxwwmrtisblb3nfwjbgzwiiw38hasbqogxvxkbpg8tzv5qloney3pob' );
define( 'AUTH_SALT',        's0agqnzode07n7qovefp9u7pysdl6sdnzvi75dw0fiusvxqu59oghinn9zaonluv' );
define( 'SECURE_AUTH_SALT', 'mkfunz8wavmwjdpiyj4ym97t9dmo9eoini0dlbs9iwdscfqcxisbkrbbrzui9lb9' );
define( 'LOGGED_IN_SALT',   'f8nsjdcffazc7esjefzf2j6p7oecgc4wdscqgy8wtgadkv8atv0bwr5patbkhqiq' );
define( 'NONCE_SALT',       'cb2mf4zaex7sk3uotvhheqzuaamukic2wdhzre9t9i5jgti3svanqw1icolhv0gq' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wpxx_';
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
define('WP_HOME', 'http://shopclothers.kesug.com');
define('WP_SITEURL', 'http://shopclothers.kesug.com');
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';