<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

if ( ! class_exists( 'Thrive_Theme_Cloud_Element_Abstract' ) ) {
	require_once ARCHITECT_INTEGRATION_PATH . '/classes/class-thrive-theme-cloud-element-abstract.php';
}

/**
 * Class Thrive_Section_Element
 */
class Thrive_Theme_Section_Element extends Thrive_Theme_Cloud_Element_Abstract {
	/**
	 * Name of the element
	 *
	 * @return string
	 */
	public function name() {
		return __( 'Section', THEME_DOMAIN );
	}

	/**
	 * WordPress element identifier
	 *
	 * @return string
	 */
	public function identifier() {
		return '.theme-section > div';
	}

	/**
	 * Temporary hide this
	 */
	public function hide() {
		return true;
	}

	/**
	 * All these elements act as placeholders
	 *
	 * @return true
	 */
	public function is_placeholder() {
		return false;
	}

	/**
	 * Add the theme section component
	 *
	 * @return array
	 */
	public function own_components() {
		$components = parent::own_components();

		$suffix = [ ' p', ' li', ' label', ' .tcb-plain-text' ];

		$background_selector = '.section-background';
		$content_selector    = '.section-content';

		$components['layout']['config']['MarginAndPadding']['padding_to'] = $content_selector;

		$components['borders']['config']['to'] = $background_selector;
		$components['shadow']['config']['to']  = $background_selector;

		$components['typography']['config']['to']                       = $content_selector;
		$components['typography']['config']['FontSize']['css_suffix']   = $suffix;
		$components['typography']['config']['TextStyle']['css_suffix']  = $suffix;
		$components['typography']['config']['LineHeight']['css_suffix'] = $suffix;
		$components['typography']['config']['FontColor']['css_suffix']  = $suffix;
		$components['typography']['config']['FontFace']['css_suffix']   = array_merge( $suffix, [ ' h1', ' h2', ' h3', ' h4', ' h5', ' h6' ] );

		$components['background'] = [
			'config'            => [ 'to' => $background_selector ],
			'disabled_controls' => [],
		];

		$components['animation']  = [ 'hidden' => true ];
		$components['decoration'] = [
			'config' => [ 'to' => $background_selector ],
			'order'  => 50,
		];

		$components['theme_section'] = [
			'config' => [
				'SectionTemplates'   => [
					'config'  => [
						'label' => __( 'Template', THEME_DOMAIN ),
					],
					'extends' => 'ModalPicker',
				],
				'StretchBackground'  => [
					'config'  => [
						'name'    => '',
						'label'   => __( 'Stretch background to full width', THEME_DOMAIN ),
						'default' => true,
					],
					'extends' => 'Switch',
				],
				'InheritContentSize' => [
					'config'  => [
						'name'    => '',
						'label'   => __( 'Inherit content size from layout', THEME_DOMAIN ),
						'default' => true,
					],
					'extends' => 'Switch',
				],
				'MinWidth'           => [
					'config'  => [
						'default' => '1080',
						'min'     => '1',
						'max'     => '1980',
						'label'   => __( 'Section Minimum Width', THEME_DOMAIN ),
						'um'      => [ 'px', '%' ],
						'css'     => 'min-width',
					],
					'extends' => 'Slider',
				],
				'ContentWidth'       => [
					'config'  => [
						'default' => '1080',
						'min'     => '1',
						'max'     => '1980',
						'label'   => __( 'Content Width', THEME_DOMAIN ),
						'um'      => [ 'px' ],
						'css'     => 'max-width',
					],
					'css_suffix'      => " {$content_selector}",
					'extends' => 'Slider',
				],
				'StretchContent'     => [
					'config'  => [
						'name'    => '',
						'label'   => __( 'Stretch content to fullwidth', THEME_DOMAIN ),
						'default' => true,
					],
					'extends' => 'Switch',
				],
				'SectionHeight'      => [
					'config'  => [
						'default' => '80',
						'min'     => '1',
						'max'     => '1000',
						'label'   => __( 'Section Minimum Height', THEME_DOMAIN ),
						'um'      => [ 'px', 'vh' ],
						'css'     => 'min-height',
					],
					'css_suffix'      => " {$content_selector}",
					'extends' => 'Slider',
				],
				'VerticalPosition'   => [
					'config'  => [
						'name'    => __( 'Vertical Position', THEME_DOMAIN ),
						'buttons' => [
							[
								'icon'    => 'top',
								'default' => true,
								'value'   => '',
							],
							[
								'icon'  => 'vertical',
								'value' => 'center',
							],
							[
								'icon'  => 'bot',
								'value' => 'flex-end',
							],
						],
					],
					'to'      => $content_selector,
					'extends' => 'ButtonGroup',
				],
				'Visibility'         => [
					'config'  => [
						'name'    => '',
						'label'   => __( 'Visibility', THEME_DOMAIN ),
						'default' => true,
					],
					'extends' => 'Switch',
				],
				'Position'           => [
					'config'  => [
						'name'    => __( 'Position', THEME_DOMAIN ),
						'options' => [
							[
								'name'  => __( 'Right', THEME_DOMAIN ),
								'value' => 'right',
							],
							[
								'name'  => __( 'Left', THEME_DOMAIN ),
								'value' => 'left',
							],
						],
						'default' => 'left',
					],
					'extends' => 'Select',
				],
			],
		];

		return $components;
	}
}

return new Thrive_Theme_Section_Element( 'theme_section' );
