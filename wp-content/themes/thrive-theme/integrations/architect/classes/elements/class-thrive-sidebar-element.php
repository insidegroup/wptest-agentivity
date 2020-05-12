<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Thrive_Sidebar_Element
 */
class Thrive_Sidebar_Element extends Thrive_Theme_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Widget Area', THEME_DOMAIN );
	}

	/**
	 * Return icon class needed for display in menu
	 *
	 * @return string
	 */
	public function icon() {
		return 'sidebar';
	}

	/**
	 * Wordpress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.widget-area';
	}

	/**
	 * @return string
	 */
	protected function html() {
		return $this->html_placeholder( __( 'Insert Widget Area', THEME_DOMAIN ) );
	}

	/**
	 * This element is a shortcode
	 * @return bool
	 */
	public function is_shortcode() {
		return true;
	}

	/**
	 * Return the shortcode tag of the element.
	 *
	 * @return string
	 */
	public function shortcode() {
		return 'thrive_widget_area';
	}

	/**
	 * Component and control config
	 *
	 * @return array
	 */
	public function own_components() {
		$default = parent::own_components();

		return array_merge( $default, [
			'thrive_widget_area' => [
				'order'  => 1,
				'config' => [
					'Orientation' => [
						'config'  => [
							'name'    => __( 'Orientation', THEME_DOMAIN ),
							'buttons' => [
								[
									'value'   => 'column',
									'text'    => __( 'Column', THEME_DOMAIN ),
									'default' => true,
								],
								[
									'value' => 'row',
									'text'  => __( 'Row', THEME_DOMAIN ),
								],
							],
						],
						'extends' => 'ButtonGroup',
					],
					'Sidebars'    => [
						'config'  => [
							'default' => 'none',
							'name'    => __( 'Source', THEME_DOMAIN ),
							'options' => Thrive_Utils::get_sidebars(),
						],
						'extends' => 'Select',
					],
				],
			],
		] );
	}
}

return new Thrive_Sidebar_Element( 'thrive_widget_area' );
