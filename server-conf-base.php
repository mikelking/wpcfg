<?php

/*
	This file contains the configuration specific to the http://dev.rd.com
	@version 1.3
*/

abstract class ServerConfigBase {
	const ENABLED          = true;
	const DISABLED         = false;
	const REPORTING_LEVEL  = 0;
	const DEFAULT_TIMEZONE = 'America/New_York';

	public $password;
	public $db;
	public $user;
	public $host;
	public $db_cfg;

	public $auth_key;
	public $secure_auth_key;
	public $logged_in_key;
	public $nonce_key;
	public $auth_salt;
	public $secure_auth_salt;
	public $logged_in_salt;
	public $nonce_salt;

	public $memcached_servers;
	public $wp_caching;
	public $default_time_zone;
	public $error_level;
	public $reporting_level;

	public $wpdbg;
	public $dbg_log;
	public $show_errors;
	public $script_dbg;
	public $save_queries;
	public $mke_api;
	public $mke_api_response;
	public $mke_api_request;

	public function __construct() {
		$this->set_db_credentials();
		$this->set_keys_and_salts();
		$this->set_caching_options();
		$this->set_hyperdb_cfg();
		$this->set_logging_options();
		$this->set_debug_options();
	}

	abstract public function set_db_credentials();

	abstract public function set_keys_and_salts();

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


}
