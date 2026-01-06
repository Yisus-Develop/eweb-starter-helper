<?php
/**
 * Elementor Cleanup Component
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Elementor {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Disable Elementor default colors and fonts
		add_action( 'after_setup_theme', [ $this, 'disable_elementor_defaults' ] );
	}

	/**
	 * Disable Elementor default colors and fonts
	 */
	public function disable_elementor_defaults() {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
	}
}
