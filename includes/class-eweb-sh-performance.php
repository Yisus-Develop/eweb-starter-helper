<?php
/**
 * Performance Optimization Module.
 *
 * Disables unnecessary WordPress features to improve page load speed.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Performance
 */
class EWEB_SH_Performance {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Performance|null
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Performance
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
		if ( EWEB_SH_Settings::is_module_active( 'disable_emojis' ) ) {
			$this->disable_emojis();
		}
		add_action( 'init', array( $this, 'disable_embeds' ), 9999 );
		add_action( 'init', array( $this, 'heartbeat_control' ), 1 );
	}

	/**
	 * Disable emojis in the front-end and admin.
	 */
	private function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
	}

	/**
	 * Disable emojis in TinyMCE editor.
	 *
	 * @param array $plugins Array of plugins.
	 * @return array
	 */
	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		}
		return array();
	}

	/**
	 * Disable oEmbed auto-discovery.
	 */
	public function disable_embeds() {
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}

	/**
	 * Control the Frequency of the Heartbeat API.
	 */
	public function heartbeat_control() {
		add_filter(
			'heartbeat_settings',
			function ( $settings ) {
				$settings['interval'] = 60;
				return $settings;
			}
		);
	}
}
