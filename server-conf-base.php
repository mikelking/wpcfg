<?php

/*
    This file contains the configuration specific to the https://github.com/mikelking/wpcfg
    @version 2.0
*/

require( 'cli-controller.php' );

/**
 * Class ServerConfigBase
 *
 * The grouping are intentional to make the settings more readable.
 */
abstract class ServerConfigBase {
    const VERSION          = '2.0';
    const REVISION_FILE    = 'revision';
    const SITE_ID          = 0; // used for single site 2 multi site consistency
    const SITENAME         = ''; // user for wp cli
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

    // Keys
    const AUTH_KEY        = 'AUTH_KEY';
    const SECURE_AUTH_KEY = 'SECURE_AUTH_KEY';
    const LOGGED_IN_KEY   = 'LOGGED_IN_KEY';
    const NONCE_KEY       = 'NONCE_KEY';

    // Salts
    const SITE_SALT        = ''; // Used in the salt and key generator
    const AUTH_SALT        = 'AUTH_SALT';
    const SECURE_AUTH_SALT = 'SECURE_AUTH_SALT';
    const LOGGED_IN_SALT   = 'LOGGED_IN_SALT';
    const NONCE_SALT       = 'NONCE_SALT';
    const CACHE_SALT       = 'WP_CACHE_KEY_SALT';

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
    const WP_POST_REVISIONS     = 10;

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
    public $auth_key;
    public $secure_auth_key;
    public $logged_in_key;
    public $nonce_key;
    public $auth_salt;
    public $secure_auth_salt;
    public $logged_in_salt;
    public $nonce_salt;
    public $cache_salt;
    public $sitename;
    public $revision;


    public function __construct() {
        $this->auth_key = $this->get_salted_hash( static::AUTH_KEY );
        $this->secure_auth_key = $this->get_salted_hash( static::SECURE_AUTH_KEY );
        $this->logged_in_key = $this->get_salted_hash( static::LOGGED_IN_KEY );
        $this->nonce_key = $this->get_salted_hash( static::NONCE_KEY );
        $this->auth_salt = $this->get_salted_hash( static::AUTH_SALT );
        $this->secure_auth_salt = $this->get_salted_hash( static::SECURE_AUTH_SALT );
        $this->logged_in_salt = $this->get_salted_hash( static::LOGGED_IN_SALT );
        $this->nonce_salt = $this->get_salted_hash( static::NONCE_SALT );
        $this->cache_salt = $this->get_salted_hash( static::CACHE_SALT );
        $this->sitename = $this->get_sitename();
        $this->revision = $this->get_revision();
    }

    public function get_version() {
        return( static::class . ' Version: ' . static::VERSION . PHP_EOL );
    }

    public function get_revision() {
        $revision = 0;
        if ( file_exists( __DIR__ .  '/' . static::REVISION_FILE ) ) {
            // https://secure.php.net/manual/en/function.file-get-contents.php
            $revision = file_get_contents( __DIR__ .  '/' . static::REVISION_FILE, false, null, 0, 7 );
        }
        return( $revision );
    }

    public function get_salted_hash( $key ) {
        return( sha1( static::SITE_SALT . $key ) );
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
        }
        return( $this->sitename = $_SERVER['REQUEST_SCHEME'] . self::PROTOCOL_DELIM . $_SERVER['HTTP_HOST'] );
    }

    function __toString() {
        $fmt = "define( %s, '%s' );\n";
        //print( $this->sitename );

        $output  = sprintf( $fmt, static::AUTH_KEY, $this->auth_key );
        $output .= sprintf( $fmt, static::SECURE_AUTH_KEY, $this->secure_auth_key );
        $output .= sprintf( $fmt, static::LOGGED_IN_KEY, $this->logged_in_key );
        $output .= sprintf( $fmt, static::NONCE_KEY, $this->nonce_key );
        $output .= sprintf( $fmt, static::AUTH_SALT, $this->auth_salt );
        $output .= sprintf( $fmt, static::SECURE_AUTH_SALT, $this->secure_auth_salt );
        $output .= sprintf( $fmt, static::LOGGED_IN_SALT, $this->logged_in_salt );
        $output .= sprintf( $fmt, static::NONCE_SALT, $this->nonce_salt );
        $output .= sprintf( $fmt, static::CACHE_SALT, $this->cache_salt );
        $output .= sprintf( $fmt, 'WP_ALLOW_MULTISITE', static::WP_ALLOW_MULTISITE );
        $output .= sprintf( $fmt, 'MULTISITE', static::MULTISITE );
        $output .= sprintf( $fmt, 'SUBDOMAIN_INSTALL', static::SUBDOMAIN_INSTALL );
        $output .= sprintf( $fmt, 'PATH_CURRENT_SITE', static::PATH_CURRENT_SITE );
        $output .= sprintf( $fmt, 'SITE_ID_CURRENT_SITE', static::SITE_ID_CURRENT_SITE );
        $output .= sprintf( $fmt, 'BLOG_ID_CURRENT_SITE', static::BLOG_ID_CURRENT_SITE );
        $output .= sprintf( $fmt, 'COOKIE_DOMAIN', $this->sitename );
        //$output .= sprintf( $fmt, static::SECURE_AUTH_KEY, static::MULTISITE );
        //$output .= sprintf( $fmt, static::SECURE_AUTH_KEY, static::MULTISITE );

        $output .= sprintf( 'Current deployed version: %s', $this->get_revision() );

        return( $output );
    }
}
