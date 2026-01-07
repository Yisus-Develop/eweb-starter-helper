<?php
/**
 * Elementor Widgets Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Elementor_Widgets {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	public function register_widgets( $widgets_manager ) {
		require_once EWEB_SH_PATH . 'includes/elementor/class-eweb-sh-copyright-widget.php';
		$widgets_manager->register( new \EWEB_SH_Copyright_Widget() );
	}
}
