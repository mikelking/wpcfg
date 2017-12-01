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
	const DB_PASSWORD = '';
	const DB_USER     = '';
	const DB_NAME     = '';
	const DB_HOST     = '';

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

	// Various WordPress settings
	const AUTO_SAVE_DELAY  = 86400; // seconds
	const BLOCK_FILE_EDITS = false;
	const BLOCK_FILE_MODS  = false;

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

	/*
		refer to: https://codex.wordpress.org/Debugging_in_WordPress
	*/
	abstract public function set_debug_options();

	/*
		The following check_ methods strongly suggest implementation hinting
		of the related abstract methods listed above.
	*/

	public function check_debug_options() {
		return(
				$this->wpdbg || $this->dbg_log ||
				$this->show_errors || $this->script_dbg ||
				$this->save_queries || $this->mke_api
		);
	}

	public function check_logging_options() {
		return(
				$this->default_timezone_set || $this->error_level ||
				$this->mke_api_response || $this->mke_api_request
		);
	}

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
