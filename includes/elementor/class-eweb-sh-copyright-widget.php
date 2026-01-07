<?php
/**
 * Elementor Copyright Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class EWEB_SH_Copyright_Widget extends Widget_Base {

	public function get_name() {
		return 'eweb_sh_copyright';
	}

	public function get_title() {
		return __( 'EWEB Copyright', 'eweb-starter-helper' );
	}

	public function get_icon() {
		return 'eicon-copyright';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$settings_global = EWEB_SH_Settings::get_instance();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'eweb-starter-helper' ),
			]
		);

		$this->add_control(
			'prefix',
			[
				'label' => __( 'Prefix', 'eweb-starter-helper' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Copyright ©', 'eweb-starter-helper' ),
				'placeholder' => __( 'e.g. Copyright ©', 'eweb-starter-helper' ),
			]
		);

		$this->add_control(
			'prefix_link',
			[
				'label' => __( 'Prefix Link', 'eweb-starter-helper' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'eweb-starter-helper' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => __( 'Suffix', 'eweb-starter-helper' ),
				'type' => Controls_Manager::TEXT,
				'default' => $settings_global->get_setting( 'company_name', __( 'Yisus Develop', 'eweb-starter-helper' ) ),
				'placeholder' => __( 'Your company name', 'eweb-starter-helper' ),
			]
		);

		$this->add_control(
			'suffix_link',
			[
				'label' => __( 'Suffix Link', 'eweb-starter-helper' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'eweb-starter-helper' ),
				'show_external' => true,
				'default' => [
					'url' => $settings_global->get_setting( 'agency_url', 'https://enlaweb.co/' ),
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'show_symbol',
			[
				'label' => __( 'Show Symbol (©)', 'eweb-starter-helper' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'eweb-starter-helper' ),
				'label_off' => __( 'Hide', 'eweb-starter-helper' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_year',
			[
				'label' => __( 'Show Current Year', 'eweb-starter-helper' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'eweb-starter-helper' ),
				'label_off' => __( 'Hide', 'eweb-starter-helper' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_agency',
			[
				'label' => __( 'Show Agency Attribution', 'eweb-starter-helper' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'eweb-starter-helper' ),
				'label_off' => __( 'Hide', 'eweb-starter-helper' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'align',
			[
				'label' => __( 'Alignment', 'eweb-starter-helper' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'eweb-starter-helper' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'eweb-starter-helper' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'eweb-starter-helper' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'eweb-starter-helper' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'eweb-starter-helper' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eweb-copyright-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .eweb-copyright-text',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings_global = EWEB_SH_Settings::get_instance();
		$current_year = date( 'Y' );

		echo '<div class="eweb-copyright-text">';
		
		// Prefix
		if ( ! empty( $settings['prefix'] ) ) {
			if ( ! empty( $settings['prefix_link']['url'] ) ) {
				$this->add_link_attributes( 'prefix_link', $settings['prefix_link'] );
				echo '<a ' . $this->get_render_attribute_string( 'prefix_link' ) . ' class="eweb-copyright-link">' . esc_html( $settings['prefix'] ) . '</a>';
			} else {
				echo esc_html( $settings['prefix'] );
			}
			echo ' ';
		}

		// Symbol
		if ( 'yes' === $settings['show_symbol'] ) {
			echo '© ';
		}

		// Year
		if ( 'yes' === $settings['show_year'] ) {
			echo $current_year . ' ';
		}

		// Suffix
		if ( ! empty( $settings['suffix'] ) ) {
			if ( ! empty( $settings['suffix_link']['url'] ) ) {
				$this->add_link_attributes( 'suffix_link', $settings['suffix_link'] );
				echo '<a ' . $this->get_render_attribute_string( 'suffix_link' ) . ' class="eweb-copyright-link">' . esc_html( $settings['suffix'] ) . '</a>';
			} else {
				echo esc_html( $settings['suffix'] );
			}
		}

		// Agency Attribution
		if ( 'yes' === $settings['show_agency'] ) {
			$agency_name = $settings_global->get_setting( 'agency_name', 'Yisus Develop' );
			$agency_url = $settings_global->get_setting( 'agency_url', 'https://enlaweb.co/' );

			echo sprintf( 
				' | ' . __( 'Powered by %s', 'eweb-starter-helper' ), 
				'<a href="' . esc_url( $agency_url ) . '" target="_blank" rel="noopener" class="eweb-copyright-link">' . esc_html( $agency_name ) . '</a>' 
			);
		}

		echo '</div>';

		// Add inline style for links to inherit text color by default but allow overriding
		?>
		<style>
			.eweb-copyright-text .eweb-copyright-link {
				color: inherit;
				text-decoration: none;
				transition: opacity 0.3s;
			}
			.eweb-copyright-text .eweb-copyright-link:hover {
				opacity: 0.8;
			}
		</style>
		<?php
	}
}
