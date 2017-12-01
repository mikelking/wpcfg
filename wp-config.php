<?php
/**
 * A generic and mostly abstracted Wp Config designed to allow configuration injection
 * based upon the apache server environment.
 * Add the appropriate Set EnvIf directive to your vhost conf and it's magickal.
 *
 * One can also override PHP.ini directive here by adding lines like:
 * @ini_set( 'display_errors', 'On' );
 *
 * Finally since WordPress will search the local path and next higher level there is no
 * need to place this in the WordPress install directory.
 *
 * Ultimately this may need to become some sort of additional composer project.
 */

require( 'class_server_conf_finder.php' );
$scf = new ServerConfFinder();

define( 'DEFAULT_TIMEZONE', $scf->server_cfg->DEFAULT_TIMEZONE );

define( 'DB_NAME', $scf->server_cfg->DB_NAME );
define( 'DB_USER', $scf->server_cfg->DB_USER );
define( 'DB_PASSWORD', $scf->server_cfg->DB_PASSWORD );
define( 'DB_HOST', $scf->server_cfg->DB_HOST );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

define( 'WP_DEBUG', $scf->server_cfg->DEBUG );
define( 'WP_DEBUG_LOG', $scf->server_cfg->LOG_ERRORS );
define( 'WP_DEBUG_DISPLAY', $scf->server_cfg->SHOW_ERRORS );
define( 'SCRIPT_DEBUG', $scf->server_cfg->SCRIPT_DEBUG );
define( 'SAVEQUERIES', $scf->server_cfg->SAVE_QUERIES );

define( 'DISALLOW_FILE_EDIT', $scf->server_cfg->BLOCK_FILE_EDITS );
define( 'DISALLOW_FILE_MODS', $scf->server_cfg->BLOCK_FILE_MODS );
define( 'AUTOSAVE_INTERVAL', $scf->server_cfg->AUTO_SAVE_DELAY ); // 1 day


if ( $scf->server_cfg->check_logging_options() ) {
	define( 'DEFAULT_ERROR_LEVEL', $scf->server_cfg->error_level );
	error_reporting( $scf->server_cfg->reporting_level );
}

define( 'WP_CACHE', $scf->server_cfg->WP_CACHE );
if ( isset( $scf->server_cfg->memcached_servers ) ) {
	$memcached_servers = $scf->server_cfg->memcached_servers;
}

define( 'AUTH_KEY',  $scf->server_cfg->get_auth_key() );
define( 'SECURE_AUTH_KEY',   $scf->server_cfg->get_secure_auth_key() );
define( 'LOGGED_IN_KEY', $scf->server_cfg->get_logged_in_key() );
define( 'NONCE_KEY', $scf->server_cfg->get_nonce_key() );
define( 'AUTH_SALT', $scf->server_cfg->get_auth_salt() );
define( 'SECURE_AUTH_SALT',  $scf->server_cfg->get_secure_auth_salt() );
define( 'LOGGED_IN_SALT', $scf->server_cfg->get_logged_in_salt() );
define( 'NONCE_SALT', $scf->server_cfg->get_nonce_salt() );
define( 'WP_CACHE_KEY_SALT', $scf->server_cfg->get_cache_salt() );
/**#@-*/

/** Override the WordPress setting for the Blog URL **/
/** Allow development environment movement */
define('WP_HOME', $scf->server_cfg->get_sitename());
define('WP_SITEURL', $scf->server_cfg->get_sitename());

define( 'WP_MEMORY_LIMIT', '512M' );

define( 'CONCATENATE_SCRIPTS', false );

//Disable internal Wp-Cron function
/* define('DISABLE_WP_CRON', true); */

$table_prefix  = 'wp_';

define ('WPLANG', '');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */

require_once( ABSPATH . 'wp-settings.php' );

