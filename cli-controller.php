<?php

/**
 * Class CLI_Controller - is not intended to be instantiated on it's own, but
 * as an extension in a descendant. The descendant controller is intended to
 * be the heart of your CLI based application. This system makes use of the
 * Late Static Bindings architecture added to PHP in 5.3.
 *
 * @see http://php.net/oop5.late-static-bindings
 *
 */
class CLI_Controller {
	const VERSION       = '1.0';
	const MIN_ARG_COUNT = 2;

	public $arg_count;
	public $arg_values;

	public function __construct( $argc, $argv ) {
		$this->arg_count  = $argc;
		$this->arg_values = $argv;

		if ( $this->is_cli() && $this->arg_count >= static::MIN_ARG_COUNT ) {
			$this->parse_opts();
		}
	}

	public static function is_cli() {
		$sapi_type = php_sapi_name();
		return ( $sapi_type === 'cli' || ( substr($sapi_type, 0, 3 ) == 'cgi' ) );
	}

	public function get_version() {
		return( static::class . ' Version: ' . static::VERSION . PHP_EOL );
	}

	public function parse_opts() {
		if ( $this->is_help() ) {
			$this->print_help_msg();
		} elseif ( $this->is_version() ) {
			die( $this->get_version() );
		} elseif ( $this->is_debug() ) {
			$this->debug_args();
		}
	}

	public function print_help_msg() {
		$help_msg = 'This is a command line PHP script with one option.' . PHP_EOL;
		$help_msg .= 'Usage:' . PHP_EOL;
		$help_msg .= $this->arg_values[0] . ' <option>' . PHP_EOL . PHP_EOL;
		$help_msg .= '<option> can be some word you would like' . PHP_EOL;
		$help_msg .= 'to print out. With the --help, -help, -h,' . PHP_EOL;
		$help_msg .= 'or -? options, you can get this help.' . PHP_EOL;
		die( $help_msg );
	}

	public function is_help() {
		return( in_array( $this->arg_values[1], array( '--help', '-h', '-?' ) ) );
	}

	public function is_version() {
		return( in_array( $this->arg_values[1], array( '-v', '--version' ) ) );
	}

	public function is_debug() {
		return( in_array( $this->arg_values[1], array( '-d', '--debug' ) ) );
	}

	public static function get_current_class() {
		$msg .= 'Get Called Class: ' . get_called_class();
		$msg .= PHP_EOL;
		$msg .= 'Static::Class: ' . static::class;
		$msg .= PHP_EOL;
		$msg .= 'Get_Class(): ' . get_class();
		$msg .= PHP_EOL;
		$msg .= 'Get_Class( $this ): ' . get_class( $this );
		$msg .= PHP_EOL;
		$msg .= PHP_EOL;

		return( $msg );
	}

	public function debug_args() {
		$msg  = 'Argc count: ' . $this->arg_count . PHP_EOL;
		$msg .= 'Argv contents: ';
		$msg .= print_r( $this->arg_values, true );
		$msg .= PHP_EOL;
		$msg .= self::get_current_class();

		die( $msg );
	}
}

//$clic = new CLI_Controller( $argc, $argv );

