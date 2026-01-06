<?php
/**
 * Branding and Admin UI Component
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Branding {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'login_headerurl', [ $this, 'login_logo_url' ] );
		add_action( 'login_headertext', [ $this, 'login_logo_title' ] );
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ] );
	}

	/**
	 * Change login logo URL to home page
	 */
	public function login_logo_url() {
		return home_url();
	}

	/**
	 * Change login logo title
	 */
	public function login_logo_title() {
		return get_bloginfo( 'name' );
	}

	/**
	 * Customize admin footer text
	 */
	public function admin_footer_text() {
		echo 'Crafted with ❤️ by <a href="https://yisusdevelop.co" target="_blank">Yisus Develop</a>';
	}
}
