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

define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true );
define( 'AUTOSAVE_INTERVAL', 86400 ); // 1 day

require( 'class_server_conf_finder.php' );
$scf = new ServerConfFinder();

if ( $scf->server_cfg->check_debug_options() ) {
	define( 'WP_DEBUG', $scf->server_cfg->wpdbg );
	define( 'WP_DEBUG_LOG', $scf->server_cfg->dbg_log );
	define( 'WP_DEBUG_DISPLAY', $scf->server_cfg->show_errors );
	define( 'SCRIPT_DEBUG', $scf->server_cfg->script_dbg );
	define( 'SAVEQUERIES', $scf->server_cfg->save_queries );
	define( 'DEBUG_MKE_API', $scf->server_cfg->mke_api );
} else {
	define( 'WP_DEBUG',false );
}

if ( $scf->server_cfg->check_logging_options() ) {
	define( 'DEFAULT_TIMEZONE', $scf->server_cfg->default_timezone_set );
	define( 'DEFAULT_ERROR_LEVEL', $scf->server_cfg->error_level );
	define( 'MKE_API_LOGGING', $scf->server_cfg->mke_api_response );
	define( 'ENHANCED_MKE_API_LOGGING', $scf->server_cfg->mke_api_request );
	error_reporting( $scf->server_cfg->reporting_level );
}

if ( $scf->server_cfg->check_caching_options() ) {
	define( 'WP_CACHE', $scf->server_cfg->wp_caching );
	$memcached_servers = $scf->server_cfg->memcached_servers;
}

define( 'DB_NAME', $scf->server_cfg->db );
define( 'DB_USER', $scf->server_cfg->user );
define( 'DB_PASSWORD', $scf->server_cfg->password );
define( 'DB_HOST', $scf->server_cfg->host );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );


define( 'AUTH_KEY',  $scf->server_cfg->auth_key );
define( 'SECURE_AUTH_KEY',   $scf->server_cfg->secure_auth_key );
define( 'LOGGED_IN_KEY', $scf->server_cfg->logged_in_key );
define( 'NONCE_KEY', $scf->server_cfg->nonce_key );
define( 'AUTH_SALT', $scf->server_cfg->auth_salt );
define( 'SECURE_AUTH_SALT',  $scf->server_cfg->secure_auth_salt );
define( 'LOGGED_IN_SALT', $scf->server_cfg->logged_in_salt );
define( 'NONCE_SALT', $scf->server_cfg->nonce_salt );
define( 'WP_CACHE_KEY_SALT', $scf->server_cfg->wp_cache_salt );
/**#@-*/

/** Override the WordPress setting for the Blog URL **/
/** Allow development environment movement */
define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );

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

