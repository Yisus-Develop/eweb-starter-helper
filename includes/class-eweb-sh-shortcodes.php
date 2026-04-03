<?php
/**
 * Global Shortcodes Module.
 *
 * Provides utility shortcodes like [eweb_year] for dynamic content.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Shortcodes
 */
class EWEB_SH_Shortcodes {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Shortcodes|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Shortcodes
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
		add_shortcode( 'eweb_year', array( $this, 'dynamic_year_shortcode' ) );
		add_shortcode( 'eweb_copyright', array( $this, 'copyright_shortcode' ) );
	}

	/**
	 * Output current year.
	 *
	 * @return string
	 */
	public function dynamic_year_shortcode() {
		return gmdate( 'Y' );
	}

	/**
	 * Output a full copyright line.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function copyright_shortcode( $atts ) {
		$args = shortcode_atts(
			array(
				'prefix' => 'Copyright',
				'suffix' => 'All Rights Reserved.',
			),
			$atts,
			'eweb_copyright'
		);

		return sprintf(
			/* translators: 1: Prefix, 2: Year, 3: Site Name, 4: Suffix */
			'%1$s &copy; %2$s %3$s. %4$s',
			esc_html( $args['prefix'] ),
			esc_html( gmdate( 'Y' ) ),
			esc_html( get_bloginfo( 'name' ) ),
			esc_html( $args['suffix'] )
		);
	}
}
