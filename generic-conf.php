<?php

/*
	This is a generic configuration object copy is and replace the %% values as appropriate.
	Normally the host for a db connection is a host name or IP address however if you database runs on a socket ot alternative port then you may need something more advanced.

	$this->host     = '127.0.0.1:3360';
	$this->host     = 'localhost:/mysql/data_5.6.19/5.6.19.sock';
*/
require(__DIR__ . '/server-conf-base.php');

class ServerConfig extends ServerConfigBase {

	public function set_db_credentials() {
		$this->password = '%%PASSWORD%%';
		$this->db       = '%%DATABASE%%';
		$this->user     = '%%USER%%';
		$this->host     = '%%HOST%%';
	}

	public function set_keys_and_salts() {
		$this->auth_key         = '%%AUTHKEY%%';
		$this->secure_auth_key  = '%%SECAUTHKEY%%';
		$this->logged_in_key    = '%%LOGGEDINKEY%%';
		$this->nonce_key        = '%%NONCE%%';
		$this->auth_salt        = '%%AUTHSALT%%';
		$this->secure_auth_salt = '%%SECAUTHSALT%%';
		$this->logged_in_salt   = '%%LOGGEDINSALT%%';
		$this->nonce_salt       = '%%NONCESALT%%';
		$this->wp_cache_salt    = '%%CACHESALT%%';
	}

	public function set_caching_options() {
		$this->wp_caching = self::ENABLED;

		$this->memcached_servers = array(
			'default' => array(
				'127.0.0.1:11211'
			)
		);
	}

	/*
		refer to: https://codex.wordpress.org/Debugging_in_WordPress
	*/
	public function set_debug_options() {
		$this->wpdbg        = self::DISABLED;
		$this->dbg_log      = self::DISABLED;
		$this->show_errors  = self::DISABLED;
		$this->script_dbg   = self::DISABLED;
		$this->save_queries = self::DISABLED;
		$this->mke_api      = self::ENABLED;
	}


	public function set_logging_options() {
		$this->default_timezone_set = self::DEFAULT_TIMEZONE;
		$this->error_level          = E_ALL;
		$this->reporting_level      = self::REPORTING_LEVEL;
		$this->mke_api_response     = self::ENABLED;
		$this->mke_api_request      = self::ENABLED;

	}

	public function set_hyperdb_cfg() {
		$db_cfg = array(
			'host'          => $this->host,
			'user'          => $this->user,
			'password'      => $this->password,
			'name'          => $this->db,
			'write'         => 1,
			'read'          => 1,
			'dataset'       => 'global',
			'timeout'       => 0.2,
			'lag_threshold' => 2,
		);
	}
}
