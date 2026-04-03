<?php
/**
 * Project Optimization Module.
 *
 * Handles general WordPress cleanups and head optimizations.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Optimization
 */
class EWEB_SH_Optimization {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Optimization|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Optimization
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
		if ( ! is_admin() ) {
			add_action( 'init', array( $this, 'cleanup_head' ) );
			add_filter( 'the_generator', '__return_empty_string' );
		}
	}

	/**
	 * Clean up WordPress head from unnecessary tags.
	 */
	public function cleanup_head() {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	}
}
