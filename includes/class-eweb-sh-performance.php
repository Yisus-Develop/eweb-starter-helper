<?php
/**
 * Performance Class
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Performance {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Disable Emojis
		add_action( 'init', [ $this, 'disable_emojis' ] );
		
		// Disable Embeds
		add_action( 'wp_footer', [ $this, 'disable_embeds' ] );

		// Heartbeat Control (limit to 60s)
		add_filter( 'heartbeat_settings', [ $this, 'heartbeat_control' ] );
	}

	public function disable_emojis() {
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
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	public function disable_embeds() {
		wp_deregister_script( 'wp-embed' );
	}

	public function heartbeat_control( $settings ) {
		$settings['interval'] = 60; // 60 seconds
		return $settings;
	}
}
