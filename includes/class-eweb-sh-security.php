<?php
/**
 * Hardening Security Module.
 *
 * Implements security measures like removing X-Pingback, protecting XML-RPC,
 * anti-enumeration, and footprint removal.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Security
 */
class EWEB_SH_Security {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Security|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Security
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( EWEB_SH_Settings::is_module_active( 'security' ) ) {
			// XML-RPC and Pingbacks.
			add_filter( 'wp_headers', array( $this, 'remove_x_pingback' ) );
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'xmlrpc_methods', array( $this, 'remove_xmlrpc_pingback_ping' ) );

			// Anti-Enumeration & Footprint Removal (Elite Standard).
			add_filter( 'rest_endpoints', array( $this, 'block_rest_user_enumeration' ) );
			remove_action( 'wp_head', 'wp_generator' );
			add_filter( 'the_generator', '__return_empty_string' );

			// Secondary Layer Protection for sensitive files.
			add_action( 'init', array( $this, 'block_sensitive_files_access' ) );
		}
	}

	/**
	 * Remove pingback method from XML-RPC.
	 *
	 * @param array $methods XML-RPC methods.
	 * @return array
	 */
	public function remove_xmlrpc_pingback_ping( $methods ) {
		unset( $methods['pingback.ping'] );
		return $methods;
	}

	/**
	 * Remove X-Pingback from HTTP headers.
	 *
	 * @param array $headers HTTP headers.
	 * @return array
	 */
	public function remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}

	/**
	 * Block REST API User Enumeration for anonymous users.
	 *
	 * @param array $endpoints REST API endpoints.
	 * @return array
	 */
	public function block_rest_user_enumeration( $endpoints ) {
		if ( isset( $endpoints['/wp/v2/users'] ) && ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users'] );
		}
		if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) && ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}

	/**
	 * Force 403 on sensitive files via PHP.
	 */
	public function block_sensitive_files_access() {
		if ( isset( $_SERVER['REQUEST_URI'] ) && preg_match( '/(license\.txt|readme\.html|error_log)/i', $_SERVER['REQUEST_URI'] ) ) {
			status_header( 403 );
			die( 'Access Forbidden' );
		}
	}
}
