<?php
/**
 * Elementor Integration Module.
 *
 * Handles general Elementor cleanups and optimizations.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Elementor
 */
class EWEB_SH_Elementor {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Elementor|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Elementor
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
		if ( EWEB_SH_Settings::is_module_active( 'elementor' ) ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'remove_elementor_unused_scripts' ), 999 );
		}
	}

	/**
	 * Remove unnecessary Elementor scripts and styles.
	 */
	public function remove_elementor_unused_scripts() {
		wp_dequeue_style( 'elementor-icons' );
	}
}
