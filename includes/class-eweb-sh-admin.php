<?php
/**
 * Admin Customization Module.
 *
 * Handles administrative UI changes like footer signature and dashboard cleanup.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Admin
 */
class EWEB_SH_Admin {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Admin|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Admin
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
		if ( is_admin() ) {
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_signature' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'cleanup_dashboard' ), 999 );
			add_action( 'admin_bar_menu', array( $this, 'cleanup_admin_bar' ), 999 );
		}
	}

	/**
	 * Custom Admin Footer signature.
	 *
	 * @return string
	 */
	public function admin_footer_signature() {
		return sprintf(
			/* translators: %s: Author link */
			esc_html__( 'Developed by %s with ❤️ and Engineering.', 'eweb-starter-helper' ),
			'<a href="https://github.com/Yisus-Develop" target="_blank">Yisus Develop</a>'
		);
	}

	/**
	 * Remove unnecessary dashboard widgets.
	 */
	public function cleanup_dashboard() {
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	}

	/**
	 * Remove items from Admin Bar.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
	 */
	public function cleanup_admin_bar( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
		$wp_admin_bar->remove_node( 'comments' );
	}
}
