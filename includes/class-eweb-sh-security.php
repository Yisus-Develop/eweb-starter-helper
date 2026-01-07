<?php
/**
 * Security Class
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Security {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Hide WP Version
		add_filter( 'the_generator', '__return_empty_string' );
		
		// Disable XML-RPC
		add_filter( 'xmlrpc_enabled', '__return_false' );
		
		// Hide Login Errors
		add_filter( 'login_errors', function() {
			return __( 'Error: Invalid credentials.', 'eweb-starter-helper' );
		});

		// Remove X-Pingback Header
		add_filter( 'wp_headers', [ $this, 'remove_x_pingback' ] );
	}

	public function remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
}
