<?php
/**
 * Shortcodes Class
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Shortcodes {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'eweb_copyright', [ $this, 'render_copyright' ] );
		add_shortcode( 'eweb_year', [ $this, 'render_year' ] );
	}

	/**
	 * Render [eweb_copyright] shortcode
	 * Attributes:
	 * - company: Override default company name
	 * - agency: Override default agency name
	 * - url: Override default agency URL
	 * 
	 * Returns: © 2026 Company | Powered by Agency
	 */
	public function render_copyright( $atts ) {
		$settings = EWEB_SH_Settings::get_instance();
		
		$atts = shortcode_atts( [
			'company' => $settings->get_setting( 'company_name', 'Enlaweb' ),
			'agency'  => $settings->get_setting( 'agency_name', 'Yisus Develop' ),
			'url'     => $settings->get_setting( 'agency_url', 'https://enlaweb.co/' ),
		], $atts );

		$year = date( 'Y' );
		
		$output = '© ' . $year;
		
		if ( ! empty( $atts['company'] ) ) {
			$output .= ' ' . esc_html( $atts['company'] );
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
	 * Render [eweb_year] shortcode
	 * Returns: 2026
	 */
	public function render_year() {
		return date( 'Y' );
	}
}
