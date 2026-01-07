<?php
/**
 * Settings Class
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Settings {

	private static $instance = null;
	public $option_name = 'eweb_sh_settings';

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'settings_init' ] );
	}

	public function add_admin_menu() {
		add_menu_page(
			__( 'EWEB Helper', 'eweb-starter-helper' ),
			__( 'EWEB Helper', 'eweb-starter-helper' ),
			'manage_options',
			'eweb-sh-settings',
			[ $this, 'settings_page_html' ],
			'dashicons-admin-generic',
			60
		);
	}

	public function settings_init() {
		register_setting( 'eweb_sh_group', $this->option_name );

		// Section: Modules
		add_settings_section(
			'eweb_sh_section_modules',
			__( 'Feature Modules', 'eweb-starter-helper' ),
			null,
			'eweb-sh-settings'
		);

		$modules = $this->get_modules_list();

		foreach ( $modules as $id => $label ) {
			add_settings_field(
				'eweb_sh_module_' . $id,
				$label,
				[ $this, 'render_checkbox_field' ],
				'eweb-sh-settings',
				'eweb_sh_section_modules',
				[ 'id' => $id ]
			);
		}

		// Section: Global Data
		add_settings_section(
			'eweb_sh_section_data',
			__( 'Global Data (Shortcodes)', 'eweb-starter-helper' ),
			null,
			'eweb-sh-settings'
		);

		add_settings_field(
			'company_name',
			__( 'Company Name', 'eweb-starter-helper' ),
			[ $this, 'render_text_field' ],
			'eweb-sh-settings',
			'eweb_sh_section_data',
			[ 'id' => 'company_name', 'placeholder' => 'Enlaweb' ]
		);

		add_settings_field(
			'agency_name',
			__( 'Agency Name', 'eweb-starter-helper' ),
			[ $this, 'render_text_field' ],
			'eweb-sh-settings',
			'eweb_sh_section_data',
			[ 'id' => 'agency_name', 'placeholder' => 'Yisus Develop' ]
		);

		add_settings_field(
			'agency_url',
			__( 'Agency URL', 'eweb-starter-helper' ),
			[ $this, 'render_text_field' ],
			'eweb-sh-settings',
			'eweb_sh_section_data',
			[ 'id' => 'agency_url', 'placeholder' => 'https://enlaweb.co/' ]
		);
	}

	public function get_modules_list() {
		return [
			'svg'              => __( 'Enable Safe SVG Support', 'eweb-starter-helper' ),
			'elementor'        => __( 'Elementor Optimizations (Cleanup)', 'eweb-starter-helper' ),
			'optimization'     => __( 'Head Bloat Performance Optimizations', 'eweb-starter-helper' ),
			'branding'         => __( 'Custom Branding (Login)', 'eweb-starter-helper' ),
			'shortcodes'       => __( 'Dynamic Shortcodes (Copyright/Year)', 'eweb-starter-helper' ),
			'elementor_widget' => __( 'Native Elementor Widget (Copyright)', 'eweb-starter-helper' ),
			'duplicator'       => __( 'Master Duplicator (Post/Page/Elementor)', 'eweb-starter-helper' ),
			'security'         => __( 'Basic Security (Hide Version/XML-RPC)', 'eweb-starter-helper' ),
			'cleanup'          => __( 'Dashboard Cleanup', 'eweb-starter-helper' ),
			'env_badge'        => __( 'Environment Indicator (Admin Bar)', 'eweb-starter-helper' ),
		];
	}

	public function render_checkbox_field( $args ) {
		$options = get_option( $this->option_name, [] );
		$id      = $args['id'];
		
		// DEFAULT ON if not set
		$val = isset( $options[ $id ] ) ? $options[ $id ] : '1';
		$checked = checked( $val, '1', false );

		printf(
			'<input type="checkbox" name="%s[%s]" value="1" %s />',
			esc_attr( $this->option_name ),
			esc_attr( $id ),
			$checked
		);
	}

	public function render_text_field( $args ) {
		$options = get_option( $this->option_name, [] );
		$id      = $args['id'];
		$val     = isset( $options[ $id ] ) ? $options[ $id ] : '';
		$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';

		printf(
			'<input type="text" name="%s[%s]" value="%s" class="regular-text" placeholder="%s" />',
			esc_attr( $this->option_name ),
			esc_attr( $id ),
			esc_attr( $val ),
			esc_attr( $placeholder )
		);
	}

	public function settings_page_html() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p><i><?php esc_html_e( 'Crafted with ❤️ by Yisus Develop', 'eweb-starter-helper' ); ?></i></p>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'eweb_sh_group' );
				do_settings_sections( 'eweb-sh-settings' );
				submit_button( __( 'Save Changes', 'eweb-starter-helper' ) );
				?>
			</form>
		</div>
		<?php
	}

	public function is_module_active( $module_id ) {
		$options = get_option( $this->option_name, [] );
		
		// If option is not set locally, DEFAULT to true (1)
		if ( ! isset( $options[ $module_id ] ) ) {
			return true;
		}

		return '1' === $options[ $module_id ];
	}

	public function get_setting( $key, $default = '' ) {
		$options = get_option( $this->option_name, [] );
		return isset( $options[ $key ] ) ? $options[ $key ] : $default;
	}
}
