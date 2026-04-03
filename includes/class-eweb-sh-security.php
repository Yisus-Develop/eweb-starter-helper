<?php
/**
 * Hardening Security Module.
 *
 * Implements security measures like removing X-Pingback and protecting XML-RPC.
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
			add_filter( 'wp_headers', array( $this, 'remove_x_pingback' ) );
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter(
				'xmlrpc_methods',
				function ( $methods ) {
					unset( $methods['pingback.ping'] );
					return $methods;
				}
			);
		}
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
}
