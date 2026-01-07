<?php
/**
 * SVG Support Component
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_SVG {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_filter( 'upload_mimes', [ $this, 'allow_svg' ] );
		add_filter( 'wp_check_filetype_and_ext', [ $this, 'fix_svg_mime_type' ], 10, 4 );
		add_action( 'admin_head', [ $this, 'fix_svg_display' ] );
	}

	/**
	 * Allow SVG uploads
	 */
	public function allow_svg( $mimes ) {
		$mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Fix SVG mime type detection issues in WP
	 */
	public function fix_svg_mime_type( $data, $file, $filename, $mimes ) {
		$ext = isset( $data['ext'] ) ? $data['ext'] : '';
		if ( '' === $ext ) {
			$exploded = explode( '.', $filename );
			$ext      = strtolower( end( $exploded ) );
		}

		if ( 'svg' === $ext ) {
			$data['type'] = 'image/svg+xml';
			$data['ext']  = 'svg';
		}

		return $data;
	}

	/**
	 * Add CSS to fix SVG display in Media Library
	 */
	public function fix_svg_display() {
		echo '<style>
			.attachment-266x266, .thumbnail img[src$=".svg"] { width: 100% !important; height: auto !important; }
		</style>';
	}
}
