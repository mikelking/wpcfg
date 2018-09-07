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

require( __DIR__ . '/class_server_conf_finder.php' );
$scf = new ServerConfFinder();

define( 'DEFAULT_TIMEZONE', $scf->server_cfg::DEFAULT_TIMEZONE );

define( 'DB_NAME', $scf->server_cfg::DB_NAME );
define( 'DB_USER', $scf->server_cfg::DB_USER );
define( 'DB_PASSWORD', $scf->server_cfg::DB_PASSWORD );
define( 'DB_HOST', $scf->server_cfg::DB_HOST );
define( 'DB_CHARSET', $scf->server_cfg::DB_CHARSET );
define( 'DB_COLLATE', $scf->server_cfg::DB_COLLATE );
$table_prefix  = $scf->server_cfg::TABLE_PREFIX;

define( 'WP_DEBUG', $scf->server_cfg::DEBUG );
define( 'WP_DEBUG_LOG', $scf->server_cfg::LOG_ERRORS );
define( 'WP_DEBUG_DISPLAY', $scf->server_cfg::SHOW_ERRORS );
define( 'SCRIPT_DEBUG', $scf->server_cfg::SCRIPT_DEBUG );
define( 'SAVEQUERIES', $scf->server_cfg::SAVE_QUERIES );

define( 'WP_MEMORY_LIMIT', $scf->server_cfg::WP_MEMORY_LIMIT );
define( 'CONCATENATE_SCRIPTS', $scf->server_cfg::CONCATENATE_SCRIPTS );

umask( $scf->server_cfg::DEFAULT_UMASK );
define( 'FS_CHMOD_DIR', $scf->server_cfg::FS_CHMOD_DIR );
define( 'FS_CHMOD_FILE', $scf->server_cfg::FS_CHMOD_FILE );
define( 'DISALLOW_FILE_EDIT', $scf->server_cfg::BLOCK_FILE_EDITS );
define( 'DISALLOW_FILE_MODS', $scf->server_cfg::BLOCK_FILE_MODS );
define( 'AUTOSAVE_INTERVAL', $scf->server_cfg::AUTO_SAVE_DELAY ); // 1 day

define( 'DEFAULT_ERROR_LEVEL', $scf->server_cfg::ERROR_LEVEL );
error_reporting( $scf->server_cfg::REPORTING_LEVEL );

define( 'WP_CACHE', $scf->server_cfg::WP_CACHE );
if ( isset( $scf->server_cfg->memcached_servers ) ) {
	$memcached_servers = $scf->server_cfg->memcached_servers;
}

define( 'AUTH_KEY', $scf->server_cfg->auth_key );
define( 'SECURE_AUTH_KEY', $scf->server_cfg->secure_auth_key );
define( 'LOGGED_IN_KEY', $scf->server_cfg->logged_in_key );
define( 'NONCE_KEY', $scf->server_cfg->nonce_key );
define( 'AUTH_SALT', $scf->server_cfg->auth_salt );
define( 'SECURE_AUTH_SALT', $scf->server_cfg->secure_auth_salt );
define( 'LOGGED_IN_SALT', $scf->server_cfg->logged_in_salt );
define( 'NONCE_SALT', $scf->server_cfg->nonce_salt );
define( 'WP_CACHE_KEY_SALT', $scf->server_cfg->cache_salt );

/**
 * Needs to check multisite constant and adjust CD accordingly
 * temporarily removing $scf->server_cfg::COOKIE_DOMAIN
 *
 * All of my attempts to add some sort of logical hydration have
 * failed. Since the cookie domain is not necessary for wp cli modes
 * I think it is safest to just live with the super global.
 *
 * Normally I would not leave debugging code around like this but I
 * want to investigate further.
 */
//print( '<h2>The cookie domain is: ' . $scf->server_cfg->cookie_domain . '</h2>' . PHP_EOL );
//print( '<h2>The HTTP_HOST is: ' . $_SERVER['HTTP_HOST'] . '</h2>' . PHP_EOL );
define( 'COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] );
//define( 'COOKIE_DOMAIN', $scf->server_cfg->cookie_domain );

/** Override the WordPress setting for the Blog URL **/
/** Allow development environment movement */
define( 'WP_HOME', $scf->server_cfg->sitename );
define( 'WP_SITEURL', $scf->server_cfg->sitename );

define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'WP_AUTO_UPDATE_CORE', false );
#define( 'WP_AUTO_UPDATE_CORE', 'minor' ); // Only enable minor core updates

define( 'WP_TURN_OFF_ADMIN_BAR', $scf->server_cfg::WP_TURN_OFF_ADMIN_BAR );
define( 'WP_POST_REVISIONS', $scf->server_cfg::WP_POST_REVISIONS );

//define( 'SUNRISE', $scf->server_cfg::SUNRISE );
define( 'DISABLE_WP_CRON', $scf->server_cfg::DISABLE_WP_CRON );

define( 'FORCE_SSL_LOGIN', $scf->server_cfg::FORCE_SSL_LOGIN );
define( 'FORCE_SSL_ADMIN', $scf->server_cfg::FORCE_SSL_ADMIN );

define( 'WP_ALLOW_MULTISITE', $scf->server_cfg::WP_ALLOW_MULTISITE );
define( 'MULTISITE', $scf->server_cfg::MULTISITE );
define( 'SUBDOMAIN_INSTALL', $scf->server_cfg::SUBDOMAIN_INSTALL );
define( 'DOMAIN_CURRENT_SITE', $scf->server_cfg::DOMAIN_CURRENT_SITE );
define( 'PATH_CURRENT_SITE', $scf->server_cfg::PATH_CURRENT_SITE );
define( 'SITE_ID_CURRENT_SITE', $scf->server_cfg::SITE_ID_CURRENT_SITE );
define( 'BLOG_ID_CURRENT_SITE', $scf->server_cfg::BLOG_ID_CURRENT_SITE );

define ('WPLANG', '');

/*
 * Other defined constants
 * @see https://gist.github.com/MikeNGarrett/e20d77ca8ba4ae62adf5
 */

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Sets up WordPress vars and included files. */

require_once( ABSPATH . 'wp-settings.php' );

