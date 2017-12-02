<?php

/*
	This file contains the configuration specific to the http://dev.rd.com
	@version 1.3
*/

require( 'cli-controller.php' );

/**
 * Class ServerConfigBase
 *
 * The grouping are intentional to make the settings more readable.
 */
abstract class ServerConfigBase {
	const VERSION          = '2.0';
	const SITE_ID          = 0; // used for single site 2 multi site consistency
	const SITENAME         = ''; // user for wp cli
	const SITE_SALT        = ''; // Used in the salt and key generator
	const ENABLED          = true;
	const DISABLED         = false;
	const PROTOCOL         = 'http';
	const PROTOCOL_DELIM   = '://';
	const DEFAULT_TIMEZONE = 'America/New_York';

	// DB Access
	const DB_PASSWORD  = '';
	const DB_USER      = '';
	const DB_NAME      = '';
	const DB_HOST      = '';
	const DB_CHARSET   = 'utf8';
	const DB_COLLATE   = '';
	const TABLE_PREFIX = 'wp_';

	// Caching
	const WP_CACHE = false;

	// Debugging
	const DEBUG        = false;
	const LOG_ERRORS   = false;
	const SHOW_ERRORS  = false;
	const SCRIPT_DEBUG = false;
	const SAVE_QUERIES = false;

	// Setup Logging
	const REPORTING_LEVEL  = 0;
	const ERROR_LEVEL      = E_ALL;

	// CMS Settings
	const AUTO_SAVE_DELAY       = 86400; // seconds
	const WP_TURN_OFF_ADMIN_BAR = false;
	const WP_POST_REVISIONS     = false;

	// File System
	const BLOCK_FILE_EDITS = false;
	const BLOCK_FILE_MODS  = false;
	const FS_CHMOD_DIR     = 0775;
	const FS_CHMOD_FILE    = 0664;
	const DEFAULT_UMASK    = 0002;

	// System Updates
	const AUTOMATIC_UPDATER_DISABLED = true;
	const WP_AUTO_UPDATE_CORE        = false;

	// Security SSL not necessary if you entire site is HTTPS
	const COOKIE_DOMAIN = '';
	const FORCE_SSL_LOGIN = false;
	const FORCE_SSL_ADMIN = false;

	// Performance
	const WP_MEMORY_LIMIT     = '512M';
	const CONCATENATE_SCRIPTS = false;

	// MulitSite
	const MULTISITE           = false;
	const WP_ALLOW_MULTISITE  = false;
	const SUBDOMAIN_INSTALL   = false;
	const DOMAIN_CURRENT_SITE = '';
	const PATH_CURRENT_SITE   = '/';
	const SITE_ID_CURRENT_SITE = 1;
	const BLOG_ID_CURRENT_SITE = 1;

	// Misc
	const SUNRISE         = false;
	const DISABLE_WP_CRON = false;

	public $db_cfg;
	public $memcached_servers;


	public function __construct() {
		$this->set_db_credentials();
		$this->set_keys_and_salts();
		$this->set_caching_options();
		$this->set_hyperdb_cfg();
		$this->set_logging_options();
		$this->set_debug_options();
	}

	public function get_version() {
		return( static::class . ' Version: ' . static::VERSION . PHP_EOL );
	}

	public function get_salted_hash( $key ) {
		return( sha1( static::SITE_SALT . $key ) );
	}

	public function get_auth_key() {
		return( $this->get_salted_hash( 'auth_key' ) );
	}

	public function get_secure_auth_key() {
		return( $this->get_salted_hash( 'secure_auth_key' ) );
	}


	public function get_logged_in_key() {
		return( $this->get_salted_hash( 'logged_in_key' ) );
	}

	public function get_nonce_key() {
		return( $this->get_salted_hash( 'nonce_key' ) );
	}

	public function get_auth_salt() {
		return( $this->get_salted_hash( 'auth_salt' ) );
	}

	public function get_secure_auth_salt() {
		return( $this->get_salted_hash( 'secure_auth_salt' ) );
	}

	public function get_logged_in_salt() {
		return( $this->get_salted_hash( 'logged_in_salt' ) );
	}


	public function get_nonce_salt() {
		return( $this->get_salted_hash( 'nonce_salt' ) );
	}

	public function get_cache_salt() {
		return( $this->get_salted_hash( 'cache_salt' ) );
	}

	abstract public function set_caching_options();

	abstract public function set_logging_options();

	/*
		HyperDB is tricky in that you should have distinct credentials
		for server in the database cluster
	*/
	abstract public function set_hyperdb_cfg();

	public function check_caching_options() {
		return(
				$this->wp_caching ||
				(isset($this->memcached_servers) && is_array($this->memcached_servers))
		);
	}

	/**
	 * @return string
	 */
	public function get_sitename() {
		$schema = static::PROTOCOL . self::PROTOCOL_DELIM;
		if ( CLI_Controller::is_cli() && $this->sitename !== '' ) {
			return( $schema . static::SITENAME );
		} else {
			return( $schema . $_SERVER['HTTP_HOST'] );
		}
	}

}
