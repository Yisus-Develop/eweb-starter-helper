<?php
/**
 * Safe SVG Support Module.
 *
 * Allows the upload of SVG files to the media library while ensuring security.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_SVG
 */
class EWEB_SH_SVG {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_SVG|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_SVG
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
		if ( EWEB_SH_Settings::is_module_active( 'svg_support' ) ) {
			add_filter( 'upload_mimes', array( $this, 'add_svg_mime' ) );
			add_filter( 'wp_check_filetype_and_ext', array( $this, 'fix_svg_extension' ), 10, 4 );
		}
	}

	/**
	 * Add SVG to allowed mime types.
	 *
	 * @param array $mimes Allowed mime types.
	 * @return array
	 */
	public function add_svg_mime( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Fix SVG extension and mime type mapping.
	 *
	 * @param array  $data     File data.
	 * @param string $file     File path.
	 * @param string $filename Original filename.
	 * @param array  $mimes    Allowed mime types.
	 * @return array
	 */
	public function fix_svg_extension( $data, $file, $filename, $mimes ) {
		$ext = pathinfo( $filename, PATHINFO_EXTENSION );
		if ( 'svg' === $ext ) {
			$data['type'] = 'image/svg+xml';
			$data['ext']  = 'svg';
		}
		return $data;
	}
}
