<?php
/**
 * Plugin Name: EWEB - Starter Helper
 * Description: Essential initial setup for WordPress projects: Safe SVGs, Elementor cleanup, and performance optimizations.
 * Version: 1.1.9
 * Author: Yisus Develop
 * Author URI: https://github.com/Yisus-Develop
 * License: GPL v2 or later
 * Requires at least: 6.0
 * Requires PHP: 8.1+
 * Text Domain: eweb-starter-helper
 * Domain Path: /languages
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Class.
 *
 * Handles the core initialization and module loading for the suite.
 */
class EWEB_Starter_Helper {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.1.9';

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_Starter_Helper|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_Starter_Helper
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * Initializes constants, includes, and hooks.
	 */
	private function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define plugin constants.
	 */
	private function define_constants() {
		if ( ! defined( 'EWEB_SH_VERSION' ) ) {
			define( 'EWEB_SH_VERSION', self::VERSION );
		}
		if ( ! defined( 'EWEB_SH_FILE' ) ) {
			define( 'EWEB_SH_FILE', __FILE__ );
		}
		if ( ! defined( 'EWEB_SH_PATH' ) ) {
			define( 'EWEB_SH_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'EWEB_SH_URL' ) ) {
			define( 'EWEB_SH_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'EWEB_SH_BASENAME' ) ) {
			define( 'EWEB_SH_BASENAME', plugin_basename( __FILE__ ) );
		}
	}

	/**
	 * Include required files.
	 */
	private function includes() {
		// GitHub Updater - Elite Deployment System.
		if ( ! class_exists( 'EWEB_GitHub_Updater' ) ) {
			require_once EWEB_SH_PATH . 'includes/class-eweb-github-updater.php';
		}

		// Core Classes.
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-settings.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-admin.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-duplicator.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-security.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-performance.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-optimization.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-svg.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-shortcodes.php';

		// Elementor Integration.
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-elementor.php';
		require_once EWEB_SH_PATH . 'includes/class-eweb-sh-elementor-widgets.php';
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'init_updater' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'initialize_modules' ) );
	}

	/**
	 * Initialize the GitHub Updater with Elite Standards.
	 */
	public function init_updater() {
		if ( is_admin() && class_exists( 'EWEB_GitHub_Updater' ) ) {
			new EWEB_GitHub_Updater(
				EWEB_SH_FILE,
				'Yisus-Develop',
				'eweb-starter-helper'
			);
		}
	}

	/**
	 * Load translation files.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'eweb-starter-helper', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Initialize all modules based on settings.
	 */
	public function initialize_modules() {
		// Settings is always active as it's the core.
		EWEB_SH_Settings::get_instance();

		// Initialize only if active in settings or always active for core utility.
		EWEB_SH_Admin::get_instance();
		EWEB_SH_Duplicator::get_instance();
		EWEB_SH_Security::get_instance();
		EWEB_SH_Performance::get_instance();
		EWEB_SH_Optimization::get_instance();
		EWEB_SH_SVG::get_instance();
		EWEB_SH_Shortcodes::get_instance();

		// Conditional Elementor Init.
		if ( did_action( 'elementor/loaded' ) ) {
			EWEB_SH_Elementor::get_instance();
			EWEB_SH_Elementor_Widgets::get_instance();
		}
	}
}

/**
 * Main instance helper.
 *
 * @return EWEB_Starter_Helper
 */
function eweb_sh() {
	return EWEB_Starter_Helper::get_instance();
}

// Global initialization.
eweb_sh();
