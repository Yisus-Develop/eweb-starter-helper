<?php
/**
 * Plugin Name: EWEB - Starter Helper
 * Description: Essential initial setup for WordPress projects: Safe SVGs, Elementor cleanup, and performance optimizations.
 * Version: 1.0.0
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
define( 'EWEB_SH_VERSION', '1.0.0' );
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
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-svg.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-elementor.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-optimization.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-branding.php';
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
		
		// Initialize Components
		EWEB_SH_SVG::get_instance();
		EWEB_SH_Elementor::get_instance();
		EWEB_SH_Optimization::get_instance();
		EWEB_SH_Branding::get_instance();
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
