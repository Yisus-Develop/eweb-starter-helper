<?php
/**
 * Optimization and Bloat Removal Component
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Optimization {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->cleanup_head();
		$this->disable_emojis();
		$this->security_hardening();
	}

	/**
	 * Remove bloat from WP head
	 */
	private function cleanup_head() {
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	}

	/**
	 * Disable Emojis
	 */
	private function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', [ $this, 'disable_emojis_tinymce' ] );
	}

	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, [ 'wpemoji' ] );
		}
		return [];
	}

	/**
	 * Security hardening
	 */
	private function security_hardening() {
		// Disable XML-RPC
		add_filter( 'xmlrpc_enabled', '__return_false' );
		
		// Hide WP version from feeds
		add_filter( 'the_generator', '__return_empty_string' );
	}
}
