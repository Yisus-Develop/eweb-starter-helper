<?php
/**
 * Elementor Copyright Widget.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EWEB_SH_Copyright_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'eweb_sh_copyright';
	}

	public function get_title() {
		return esc_html__( 'EWEB Copyright', 'eweb-starter-helper' );
	}

	public function get_icon() {
		return 'eicon-copyright';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function register_controls() {
		$settings_global = EWEB_SH_Settings::get_instance();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'eweb-starter-helper' ),
			)
		);

		$this->add_control(
			'prefix',
			array(
				'label'       => esc_html__( 'Prefix', 'eweb-starter-helper' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Copyright ©', 'eweb-starter-helper' ),
				'placeholder' => esc_html__( 'e.g. Copyright ©', 'eweb-starter-helper' ),
			)
		);

		$this->add_control(
			'prefix_link',
			array(
				'label'         => esc_html__( 'Prefix Link', 'eweb-starter-helper' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'eweb-starter-helper' ),
				'show_external' => true,
				'default'       => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);

		$this->add_control(
			'suffix',
			array(
				'label'       => esc_html__( 'Suffix (Company)', 'eweb-starter-helper' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Your company name', 'eweb-starter-helper' ),
				'dynamic'     => array( 'active' => true ),
			)
		);

		$this->add_control(
			'middle_text',
			array(
				'label'       => esc_html__( 'Between Year & Company', 'eweb-starter-helper' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g. - ', 'eweb-starter-helper' ),
				'default'     => ' ',
			)
		);

		$this->add_control(
			'suffix_link',
			array(
				'label'         => esc_html__( 'Suffix Link', 'eweb-starter-helper' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'eweb-starter-helper' ),
				'show_external' => true,
				'default'       => array(
					'url'         => $settings_global->get_setting( 'agency_url', 'https://enlaweb.co/' ),
					'is_external' => true,
					'nofollow'    => true,
				),
			)
		);

		$this->add_control(
			'show_symbol',
			array(
				'label'        => esc_html__( 'Show Symbol (©)', 'eweb-starter-helper' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'eweb-starter-helper' ),
				'label_off'    => esc_html__( 'Hide', 'eweb-starter-helper' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_year',
			array(
				'label'        => esc_html__( 'Show Current Year', 'eweb-starter-helper' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'eweb-starter-helper' ),
				'label_off'    => esc_html__( 'Hide', 'eweb-starter-helper' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_agency',
			array(
				'label'        => esc_html__( 'Show Agency Attribution', 'eweb-starter-helper' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'eweb-starter-helper' ),
				'label_off'    => esc_html__( 'Hide', 'eweb-starter-helper' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'agency_label',
			array(
				'label'       => esc_html__( 'Agency Label', 'eweb-starter-helper' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Powered by', 'eweb-starter-helper' ),
				'default'     => '| Powered by',
			)
		);

		$this->add_control(
			'post_agency',
			array(
				'label'       => esc_html__( 'After Agency', 'eweb-starter-helper' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'e.g. - Agency Description', 'eweb-starter-helper' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings        = $this->get_settings_for_display();
		$settings_global = EWEB_SH_Settings::get_instance();
		$current_year    = gmdate( 'Y' );
		$agency_name     = $settings_global->get_setting( 'agency_name', 'Yisus Develop' );
		$agency_url      = $settings_global->get_setting( 'agency_url', 'https://enlaweb.co/' );

		echo '<div class="eweb-copyright-text">';

		if ( ! empty( $settings['prefix'] ) ) {
			if ( ! empty( $settings['prefix_link']['url'] ) ) {
				$this->add_link_attributes( 'prefix_link', $settings['prefix_link'] );
				echo '<a ' . wp_kses_post( $this->get_render_attribute_string( 'prefix_link' ) ) . ' class="eweb-copyright-link">' . esc_html( $settings['prefix'] ) . '</a>';
			} else {
				echo esc_html( $settings['prefix'] );
			}
			echo ' ';
		}

		if ( 'yes' === ( $settings['show_symbol'] ?? 'yes' ) ) {
			echo '&copy; ';
		}

		if ( 'yes' === ( $settings['show_year'] ?? 'yes' ) ) {
			echo esc_html( $current_year );
		}

		if ( ! empty( $settings['middle_text'] ) ) {
			echo esc_html( $settings['middle_text'] );
		}

		if ( ! empty( $settings['suffix'] ) ) {
			if ( ! empty( $settings['suffix_link']['url'] ) ) {
				$this->add_link_attributes( 'suffix_link', $settings['suffix_link'] );
				echo '<a ' . wp_kses_post( $this->get_render_attribute_string( 'suffix_link' ) ) . ' class="eweb-copyright-link">' . esc_html( $settings['suffix'] ) . '</a>';
			} else {
				echo esc_html( $settings['suffix'] );
			}
		}

		if ( 'yes' === ( $settings['show_agency'] ?? 'yes' ) && ! empty( $agency_name ) ) {
			echo ' ' . esc_html( $settings['agency_label'] ?? '| Powered by' ) . ' ';
			echo '<a href="' . esc_url( $agency_url ) . '" target="_blank" rel="noopener" class="eweb-copyright-link">' . esc_html( $agency_name ) . '</a>';
			if ( ! empty( $settings['post_agency'] ) ) {
				echo ' ' . esc_html( $settings['post_agency'] );
			}
		}

		echo '</div>';
	}
}
