<?php
/**
 * Plugin Name: EWEB - Starter Helper
 * Description: Essential initial setup for WordPress projects: Safe SVGs, Elementor cleanup, and performance optimizations.
 * Version: 1.1.1
 * Author: Yisus Develop
 * Author URI: https://github.com/Yisus-Develop
 * License: GPL v2 or later
 * Requires at least: 6.0
 * Requires PHP: 8.1+
 * Text Domain: eweb-starter-helper
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants
define( 'EWEB_SH_VERSION', '1.1.1' );
define( 'EWEB_SH_PATH', plugin_dir_path( __FILE__ ) );
define( 'EWEB_SH_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Class
 */
class EWEB_Starter_Helper {

	/**
	 * Instance of this class.
	 * @var EWEB_Starter_Helper
	 */
	private static $instance = null;

	/**
	 * Get instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Load required files
	 */
	private function includes() {
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-settings.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-svg.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-elementor.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-optimization.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-shortcodes.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-duplicator.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-security.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-performance.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-admin.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-elementor-widgets.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-updater.php';
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
		
		$settings = EWEB_SH_Settings::get_instance();

		// Initialize Components based on settings
		if ( $settings->is_module_active( 'svg' ) ) {
			EWEB_SH_SVG::get_instance();
		}
		
		if ( $settings->is_module_active( 'elementor' ) ) {
			EWEB_SH_Elementor::get_instance();
		}

		if ( $settings->is_module_active( 'optimization' ) ) {
			EWEB_SH_Optimization::get_instance();
		}

		if ( $settings->is_module_active( 'optimization' ) ) {
			EWEB_SH_Optimization::get_instance();
		}

		if ( $settings->is_module_active( 'shortcodes' ) ) {
			EWEB_SH_Shortcodes::get_instance();
		}

		if ( $settings->is_module_active( 'elementor_widget' ) ) {
			EWEB_SH_Elementor_Widgets::get_instance();
		}

		if ( $settings->is_module_active( 'duplicator' ) ) {
			EWEB_SH_Duplicator::get_instance();
		}

		if ( $settings->is_module_active( 'security' ) ) {
			EWEB_SH_Security::get_instance();
		}

		if ( $settings->is_module_active( 'performance' ) || $settings->is_module_active( 'optimization' ) ) {
			EWEB_SH_Performance::get_instance();
		}

		// Admin UI module handles its own internal checks
		EWEB_SH_Admin::get_instance();

		// Initialize Update System
		if ( is_admin() ) {
			new EWEB_SH_Updater( __FILE__, 'Yisus-Develop', 'eweb-starter-helper' );
		}
	}

	/**
	 * Load text domain for translations
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'eweb-starter-helper', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

/**
 * Initialize the plugin
 */
function eweb_sh_init() {
	return EWEB_Starter_Helper::get_instance();
}

eweb_sh_init();
