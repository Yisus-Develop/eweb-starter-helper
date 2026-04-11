<?php
/**
 * Plugin Settings Module.
 *
 * Handles the administrative settings page and module management.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Settings
 */
class EWEB_SH_Settings {

	/**
	 * Instance of this class.
	 *
	 * @var EWEB_SH_Settings|null
	 */
	private static $instance = null;

	/**
	 * Settings option name.
	 *
	 * @var string
	 */
	private $option_name = 'eweb_sh_settings';

	/**
	 * Get class instance.
	 *
	 * @return EWEB_SH_Settings
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
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}

	/**
	 * Add administration menu.
	 */
	public function add_admin_menu() {
		add_options_page(
			esc_html__( 'EWEB Starter Helper', 'eweb-starter-helper' ),
			esc_html__( 'EWEB Starter', 'eweb-starter-helper' ),
			'manage_options',
			'eweb-starter-helper',
			array( $this, 'settings_page_html' )
		);
	}

	/**
	 * Initialize settings, sections, and fields.
	 */
	public function settings_init() {
		register_setting( 'eweb_sh_group', $this->option_name, array( $this, 'sanitize_settings' ) );

		// General Section.
		add_settings_section(
			'eweb_sh_section_general',
			esc_html__( 'Module Management', 'eweb-starter-helper' ),
			function () {
				echo '<p>' . esc_html__( 'Activate or deactivate the core modules for your project.', 'eweb-starter-helper' ) . '</p>';
			},
			'eweb-starter-helper'
		);

		// Modules checkboxes.
		$modules = $this->get_modules_list();
		foreach ( $modules as $id => $label ) {
			add_settings_field(
				'module_' . $id,
				$label,
				array( $this, 'render_checkbox_field' ),
				'eweb-starter-helper',
				'eweb_sh_section_general',
				array(
					'id'      => $id,
					'label'   => $label,
					'section' => 'modules',
				)
			);
		}

		// Optimization Section.
		add_settings_section(
			'eweb_sh_section_optimization',
			esc_html__( 'Performance & Security', 'eweb-starter-helper' ),
			function () {
				echo '<p>' . esc_html__( 'Configure advanced optimizations and technical protocols.', 'eweb-starter-helper' ) . '</p>';
			},
			'eweb-starter-helper'
		);

		add_settings_field(
			'disable_emojis',
			esc_html__( 'Disable Emojis', 'eweb-starter-helper' ),
			array( $this, 'render_checkbox_field' ),
			'eweb-starter-helper',
			'eweb_sh_section_optimization',
			array(
				'id'      => 'disable_emojis',
				'section' => 'optimization',
			)
		);

		add_settings_field(
			'brave_fix',
			esc_html__( 'Brave & Mobile Rendering', 'eweb-starter-helper' ),
			array( $this, 'render_checkbox_field' ),
			'eweb-starter-helper',
			'eweb_sh_section_optimization',
			array(
				'id'      => 'brave_fix',
				'section' => 'optimization',
			)
		);

		add_settings_field(
			'cache_bridge',
			esc_html__( 'Cache Optimization Bridge', 'eweb-starter-helper' ),
			array( $this, 'render_checkbox_field' ),
			'eweb-starter-helper',
			'eweb_sh_section_optimization',
			array(
				'id'      => 'cache_bridge',
				'section' => 'optimization',
			)
		);

		add_settings_field(
			'elementor_stability',
			esc_html__( 'Elementor Stability Protocols', 'eweb-starter-helper' ),
			array( $this, 'render_checkbox_field' ),
			'eweb-starter-helper',
			'eweb_sh_section_optimization',
			array(
				'id'      => 'elementor_stability',
				'section' => 'optimization',
			)
		);
	}

	/**
	 * List of available modules.
	 *
	 * @return array
	 */
	public function get_modules_list() {
		return array(
			'duplicator'  => esc_html__( 'Post Duplicator', 'eweb-starter-helper' ),
			'svg_support' => esc_html__( 'Safe SVG Support', 'eweb-starter-helper' ),
			'elementor'   => esc_html__( 'Elementor Cleanup', 'eweb-starter-helper' ),
			'security'    => esc_html__( 'Hardening Security', 'eweb-starter-helper' ),
		);
	}

	/**
	 * Sanitize settings input.
	 *
	 * @param array $input Raw input.
	 * @return array Sanitized output.
	 */
	public function sanitize_settings( $input ) {
		$output = array();
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				if ( is_array( $value ) ) {
					foreach ( $value as $sub_key => $sub_val ) {
						$output[ $key ][ $sub_key ] = ( '1' === $sub_val ) ? '1' : '0';
					}
				} else {
					$output[ $key ] = sanitize_text_field( $value );
				}
			}
		}
		return $output;
	}

	/**
	 * Render a checkbox field.
	 *
	 * @param array $args Field arguments.
	 */
	public function render_checkbox_field( $args ) {
		$options = get_option( $this->option_name, array() );
		$section = $args['section'];
		$id      = $args['id'];
		$value   = isset( $options[ $section ][ $id ] ) ? $options[ $section ][ $id ] : '0';

		printf(
			'<input type="checkbox" name="%s[%s][%s]" value="1" %s />',
			esc_attr( $this->option_name ),
			esc_attr( $section ),
			esc_attr( $id ),
			checked( '1', $value, false )
		);
	}

	/**
	 * Output the settings page HTML.
	 */
	public function settings_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'eweb_sh_group' );
				do_settings_sections( 'eweb-starter-helper' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Check if a module or setting is active.
	 *
	 * @param string $id Setting ID.
	 * @param string $section Section ID (modules or optimization).
	 * @return bool
	 */
	public static function is_active( $id, $section = 'modules' ) {
		$options = get_option( 'eweb_sh_settings', array() );
		return isset( $options[ $section ][ $id ] ) && '1' === $options[ $section ][ $id ];
	}

	/**
	 * Check if a module is active (legacy helper).
	 *
	 * @param string $module Module ID.
	 * @return bool
	 */
	public static function is_module_active( $module ) {
		return self::is_active( $module, 'modules' );
	}
}
