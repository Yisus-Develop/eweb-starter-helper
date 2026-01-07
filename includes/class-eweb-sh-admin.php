<?php
/**
 * Admin UI Class
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Admin {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$settings = EWEB_SH_Settings::get_instance();

		// Dashboard Cleanup
		if ( $settings->is_module_active( 'cleanup' ) ) {
			add_action( 'wp_dashboard_setup', [ $this, 'cleanup_dashboard' ], 999 );
		}

		// Environment Badge
		if ( $settings->is_module_active( 'env_badge' ) ) {
			add_action( 'admin_bar_menu', [ $this, 'add_environment_badge' ], 999 );
			add_action( 'admin_head', [ $this, 'add_environment_style' ] );
		}

		// Branding (Login)
		if ( $settings->is_module_active( 'branding' ) ) {
			add_action( 'login_headerurl', [ $this, 'login_logo_url' ] );
			add_action( 'login_headertext', [ $this, 'login_logo_title' ] );
		}

		add_filter( 'admin_footer_text', [ $this, 'admin_footer_signature' ] );
	}

	/**
	 * Change login logo URL to home page
	 */
	public function login_logo_url() {
		return home_url();
	}

	/**
	 * Change login logo title
	 */
	public function login_logo_title() {
		return get_bloginfo( 'name' );
	}

	public function admin_footer_signature() {
		return sprintf( 
			/* translators: %s: agency link */
			__( 'Crafted with ❤️ by %s', 'eweb-starter-helper' ), 
			'<a href="https://enlaweb.co/" target="_blank">Yisus Develop</a>' 
		);
	}

	/**
	 * Remove default dashboard widgets
	 */
	public function cleanup_dashboard() {
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );   // WP News
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // Quick Draft
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // At a Glance
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );  // Activity
		remove_action( 'welcome_panel', 'wp_welcome_panel' );           // Welcome Panel
	}

	/**
	 * Add environment badge to Admin Bar
	 */
	public function add_environment_badge( $wp_admin_bar ) {
		$env = defined( 'WP_ENVIRONMENT_TYPE' ) ? WP_ENVIRONMENT_TYPE : 'production';
		
		$colors = [
			'local'       => '#607d8b',
			'development' => '#4caf50',
			'staging'     => '#ff9800',
			'production'  => '#f44336',
		];

		$color = isset( $colors[ $env ] ) ? $colors[ $env ] : $colors['production'];

		$wp_admin_bar->add_node([
			'id'    => 'eweb-env-badge',
			'title' => '<span class="eweb-env-dot" style="background-color:' . $color . '"></span> ' . strtoupper( $env ),
			'href'  => admin_url( 'admin.php?page=eweb-sh-settings' ),
			'meta'  => [
				'class' => 'eweb-env-badge-node',
				'title' => __( 'Current Environment', 'eweb-starter-helper' ),
			],
		]);
	}

	/**
	 * Add environment badge styles
	 */
	public function add_environment_style() {
		?>
		<style>
			#wp-admin-bar-eweb-env-badge .eweb-env-dot {
				display: inline-block;
				width: 10px;
				height: 10px;
				border-radius: 50%;
				margin-right: 5px;
			}
			#wp-admin-bar-eweb-env-badge .ab-item {
				font-weight: bold !important;
			}
		</style>
		<?php
	}
}
