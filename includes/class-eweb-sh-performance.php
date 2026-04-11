<?php
/**
 * Performance Optimization Module.
 *
 * Handles hardware acceleration, cache bridges, and feature disabling.
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
		// Standard Optimizations.
		if ( EWEB_SH_Settings::is_active( 'disable_emojis', 'optimization' ) ) {
			$this->disable_emojis();
		}

		add_action( 'init', array( $this, 'disable_embeds' ), 9999 );
		add_action( 'init', array( $this, 'heartbeat_control' ), 1 );

		// Advanced Rendering (Brave/Mobile).
		if ( EWEB_SH_Settings::is_active( 'brave_fix', 'optimization' ) ) {
			add_action( 'wp_head', array( $this, 'hardware_acceleration_css' ), 1 );
		}

		// Cache Plugin Bridge.
		if ( EWEB_SH_Settings::is_active( 'cache_bridge', 'optimization' ) ) {
			add_filter( 'rocket_delay_js_exclusions', array( $this, 'wp_rocket_exclusions' ) );
			add_filter( 'rocket_exclude_js', array( $this, 'wp_rocket_exclusions' ) );
		}

		// Elementor Stability.
		if ( EWEB_SH_Settings::is_active( 'elementor_stability', 'optimization' ) ) {
			add_action( 'init', array( $this, 'elementor_stability_checks' ) );
		}
	}

	/**
	 * Inject hardware acceleration CSS for mobile and Brave compatibility.
	 */
	public function hardware_acceleration_css() {
		echo '<style>
			/* Hardware Acceleration for smooth rendering */
			.banner-anim, .mc-hero__inner, .mc-hero__title span, .u-title-reveal span {
				-webkit-backface-visibility: hidden;
				backface-visibility: hidden;
				-webkit-transform: translateZ(0);
				transform: translateZ(0);
			}
			/* Fix for Elementor shape dividers white lines */
			.elementor-shape-bottom svg {
				filter: drop-shadow(0px -1px 0px inherit);
			}
		</style>';
	}

	/**
	 * Exclude critical animation scripts from WP Rocket delay/optimization.
	 *
	 * @param array $exclusions Array of exclusions.
	 * @return array
	 */
	public function wp_rocket_exclusions( $exclusions ) {
		$critical_scripts = array(
			'gsap',
			'ScrollTrigger',
			'swiper',
			'elementor/assets/lib/swiper',
		);
		return array_merge( (array) $exclusions, $critical_scripts );
	}

	/**
	 * Disable Elementor features that often cause rendering issues.
	 */
	public function elementor_stability_checks() {
		if ( did_action( 'elementor/loaded' ) ) {
			update_option( 'elementor_optimized_css_loading', 'no' );
			update_option( 'elementor_improved_asset_loading', 'no' );
		}
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
