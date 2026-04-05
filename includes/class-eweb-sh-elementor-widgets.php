<?php
/**
 * Elementor Widgets Loader Module.
 *
 * Registers custom Elementor widgets from the suite.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Elementor_Widgets
 */
class EWEB_SH_Elementor_Widgets {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Elementor_Widgets|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Elementor_Widgets
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
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	/**
	 * Register custom Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		require_once EWEB_SH_PATH . 'includes/elementor/class-eweb-sh-copyright-widget.php';
		$widgets_manager->register( new EWEB_SH_Copyright_Widget() );
	}
}
