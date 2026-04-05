<?php
/**
 * Elementor Copyright Widget.
 *
 * Adds a dynamic copyright widget with automatic year updating.
 *
 * @package EWEB_Starter_Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EWEB_SH_Copyright_Widget
 */
class EWEB_SH_Copyright_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'eweb-sh-copyright';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'EWEB Copyright', 'eweb-starter-helper' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-copyright';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'eweb-starter-helper' ),
			)
		);

		$this->add_control(
			'prefix_text',
			array(
				'label'   => esc_html__( 'Prefix Text', 'eweb-starter-helper' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Copyright', 'eweb-starter-helper' ),
			)
		);

		$this->add_control(
			'company_name',
			array(
				'label'   => esc_html__( 'Company Name', 'eweb-starter-helper' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => get_bloginfo( 'name' ),
			)
		);

		$this->add_control(
			'show_all_rights',
			array(
				'label'     => esc_html__( 'Show All Rights Reserved', 'eweb-starter-helper' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'eweb-starter-helper' ),
				'label_off' => esc_html__( 'Hide', 'eweb-starter-helper' ),
				'default'   => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings     = $this->get_settings_for_display();
		$current_year = gmdate( 'Y' );

		printf(
			'<div class="eweb-sh-copyright">
				<span class="copyright-prefix">%s</span> 
				<span class="copyright-year">&copy; %s</span> 
				<span class="copyright-company">%s</span>
				%s
			</div>',
			esc_html( $settings['prefix_text'] ),
			esc_html( $current_year ),
			esc_html( $settings['company_name'] ),
			( 'yes' === $settings['show_all_rights'] ) ? '<span class="copyright-all-rights">. ' . esc_html__( 'All Rights Reserved.', 'eweb-starter-helper' ) . '</span>' : ''
		);
	}
}
