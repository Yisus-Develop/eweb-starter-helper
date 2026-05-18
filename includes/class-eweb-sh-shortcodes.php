<?php
/**
 * Global Shortcodes Module.
 *
 * Restores the dynamic copyright behavior used in the original plugin.
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

	public function __construct() {
		add_shortcode( 'eweb_copyright', array( $this, 'render_copyright' ) );
		add_shortcode( 'eweb_year', array( $this, 'render_year' ) );
		add_shortcode( 'eweb_copy_year', array( $this, 'render_copy_year' ) );
	}

	/**
	 * Render [eweb_copyright] shortcode.
	 *
	 * Attributes:
	 * - company: Override default company name
	 * - post_company: Extra company text after company
	 * - agency: Override default agency name
	 * - url: Override default agency URL
	 * - prefix: Optional text before copyright
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_copyright( $atts ) {
		$settings = class_exists( 'EWEB_SH_Settings' ) ? EWEB_SH_Settings::get_instance() : null;

		$default_company = ( $settings && method_exists( $settings, 'get_setting' ) ) ? $settings->get_setting( 'company_name', 'Enlaweb' ) : 'Enlaweb';
		$default_agency  = ( $settings && method_exists( $settings, 'get_setting' ) ) ? $settings->get_setting( 'agency_name', 'Yisus Develop' ) : 'Yisus Develop';
		$default_url     = ( $settings && method_exists( $settings, 'get_setting' ) ) ? $settings->get_setting( 'agency_url', 'https://enlaweb.co/' ) : 'https://enlaweb.co/';

		$atts = shortcode_atts(
			array(
				'prefix'       => '',
				'company'      => $default_company,
				'post_company' => '',
				'agency'       => $default_agency,
				'url'          => $default_url,
			),
			$atts,
			'eweb_copyright'
		);

		$year   = gmdate( 'Y' );
		$output = esc_html( $atts['prefix'] ) . '&copy; ' . esc_html( $year );

		if ( ! empty( $atts['company'] ) ) {
			$output .= ' ' . esc_html( $atts['company'] );
		}

		if ( ! empty( $atts['post_company'] ) ) {
			$output .= ' ' . esc_html( $atts['post_company'] );
		}

		if ( ! empty( $atts['agency'] ) ) {
			$output .= sprintf(
				' | ' . __( 'Powered by %s', 'eweb-starter-helper' ),
				'<a href="' . esc_url( $atts['url'] ) . '" target="_blank" rel="noopener">' . esc_html( $atts['agency'] ) . '</a>'
			);
		}

		return $output;
	}

	/**
	 * Render [eweb_copy_year] shortcode.
	 *
	 * @return string
	 */
	public function render_copy_year() {
		return '&copy; ' . gmdate( 'Y' );
	}

	/**
	 * Render [eweb_year] shortcode.
	 *
	 * @return string
	 */
	public function render_year() {
		return gmdate( 'Y' );
	}
}
