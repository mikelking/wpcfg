<?php

/*
	This system leverages the Apache server SetEvn mod.
	SetEnv ENVIRONMENT [dev, staging, test, production1, production2, production3]
	@version 1.4.0
*/

require( __DIR__ . '/cli-controller.php' );

class ServerConfFinder {
	const VERSION     = '1.4.0';
	const FILE_SUFFIX = '-conf.php';
	const DEFAULT_CFG = 'production';
	const DEV_SITE    = 'dev';
	const TEST_SITE   = 'test';
	const DIR_DELIM   = '/';

	public $server_cfg;
	public $server_name;
	public $environment;
	public $conf_file;

	public function __construct( $environment = null ) {
		$this->set_environment( $environment );
		date_default_timezone_set( "America/New_York" );
		$this->get_server_name();

		$this->get_environment();
		$this->get_config();
	}

	public function get_version() {
		return( static::class . ' Version: ' . static::VERSION . PHP_EOL );
	}

	public function set_environment( $environment = null ) {
		if ( isset( $environment ) ) {
			$this->environment = $environment;
		} elseif ( CLI_Controller::is_cli() ) {
			$this->environment = static::DEFAULT_CFG;
		}
	}

	public function is_dev_site() {
		if ( stripos( $this->server_name, self::DEV_SITE ) !== false ) {
			return( true );
		}
		return( false );
	}


	public function is_test_site() {
		if ( stripos( $this->server_name, self::TEST_SITE ) !== false ) {
			return( true );
		}
		return( false );
	}

	public function get_server_name() {
		return( $this->server_name = $_SERVER['SERVER_NAME'] );
	}

	public function get_environment() {
		if ( isset( $_SERVER['ENVIRONMENT'] ) ) {
			$this->environment = $_SERVER['ENVIRONMENT'];
		}
		return( $this->environment );
	}

	/**
	 * This system should examine the present path and one level higher for the appropriate configuration file
	 * as defined by the apache ENVIRONMENT.
	 * @return null|string
	 */
	public function get_conf_file() {
		$config_file = __DIR__ . self::DIR_DELIM . $this->environment . self::FILE_SUFFIX;
		if ( file_exists(  $config_file ) ) {
			$this->conf_file = $config_file;
		} elseif  ( file_exists( __DIR__ . self::DIR_DELIM . $config_file ) ) {
			$this->conf_file = __DIR__ . self::DIR_DELIM . $config_file;
		} else {
		   $this->conf_file = null;
		}
		return( $this->conf_file );
	}

	public function get_config() {
		if ( $this->get_conf_file() ) {
			require( $this->get_conf_file() );
			$this->server_cfg = new ServerConfig();
		} else {
			error_log ( 'Config file ' . $this->get_conf_file() . ' NOT found on ' . $this->get_server_name() . '. Fatal failure to require it.', 0 );
			$this->server_cfg = null;
		}
		return( $this->server_cfg );
	}

	public function debug_conf_file() {
		if ( file_exists( $this->get_conf_file() ) ) {
			print( 'The ' . $this->conf_file . ' environment file will be included as required.' . PHP_EOL );
		} else {
			print( 'The ' . $this->conf_file . ' environment file was not found and can not be included as 
required.' . PHP_EOL );
		}
	}
}
