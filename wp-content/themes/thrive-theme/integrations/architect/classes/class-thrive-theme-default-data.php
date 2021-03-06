<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Thrive_Theme_Default_Data
 */
class Thrive_Theme_Default_Data {

	/**
	 * Initialize Theme Default Data
	 */
	public static function init() {

		if ( Thrive_Utils::during_ajax() ) {
			return;
		}

		static::create_default();
	}

	/**
	 * Creates required default data if it doesn't already exist
	 * - used in TPM after theme is installed and activated
	 */
	public static function create_default() {

		/* if there are no skins available, just create one */
		if ( empty( Thrive_Skin_Taxonomy::get_all( 'ids' ) ) ) {
			static::create_skin();
		}

		if ( empty( thrive_skin()->get_active_typography() ) ) {
			static::create_typography();
		}
	}

	/**
	 * Create a default typography or clone one
	 *
	 * @param null|int $skin_id
	 * @param null|int $typography_source_id
	 *
	 * @return int|null|WP_Error
	 */
	public static function create_typography( $skin_id = null, $typography_source_id = null ) {
		$args = [
			'post_status' => 'publish',
			'post_type'   => THRIVE_TYPOGRAPHY,
			'post_title'  => Thrive_Typography::DEFAULT_TITLE,
			'meta_input'  => [
				'default' => 1,
				'style'   => '',
			],
		];

		//if we have typography id => we clone the typography
		if ( $typography_source_id ) {
			$args = wp_parse_args( thrive_typography( $typography_source_id )->export(), $args );
		}

		$new_typography_id = wp_insert_post( $args );

		thrive_typography( $new_typography_id )->assign_to_skin( $skin_id );

		return $new_typography_id;
	}

	/**
	 * Create a default skin only if no other skins exists on the website
	 *
	 * @return integer
	 */
	public static function create_skin() {

		$default_skin = get_term_by( 'name', Thrive_Skin::DEFAULT_SKIN, SKIN_TAXONOMY );

		if ( empty( $default_skin ) || is_wp_error( $default_skin ) ) {
			$term_insert = wp_insert_term( Thrive_Skin::DEFAULT_SKIN, SKIN_TAXONOMY );

			$skin_id = is_wp_error( $term_insert ) ? 0 : $term_insert['term_id'];
		} else {
			$skin_id = $default_skin->term_id;
		}

		if ( ! empty( $skin_id ) ) {
			Thrive_Skin_Taxonomy::set_skin_active( $skin_id );
			static::default_data_for_skin( $skin_id );
		}

		return $skin_id;
	}

	/**
	 * Generate default data for skin
	 *
	 * @param $skin_id
	 */
	private static function default_data_for_skin( $skin_id ) {

		if ( empty( $skin_id ) ) {
			$skin_id = thrive_skin()->ID;
		}

		static::create_skin_templates( $skin_id );

		static::create_typography();

		thrive_skin( $skin_id )
			->set_meta( Thrive_Skin::TAG, 'default' )
			->set_meta( Thrive_Skin::SKIN_META_PALETTES, Thrive_Defaults::skin_pallets() )
			->set_meta( Thrive_Skin::SKIN_META_VARIABLES, Thrive_Defaults::skin_variables() );

		thrive_skin()->generate_style_file();
	}

	/**
	 * Create / clone templates for a certain skin
	 *
	 * @param null $skin_id        - the skin to which the templates will be assigned
	 * @param null $source_skin_id - the skin from which the templates are copied. If this is not set => the default templates will be created
	 */
	public static function create_skin_templates( $skin_id = null, $source_skin_id = null ) {
		if ( $source_skin_id ) {
			$skin = new Thrive_Skin( $skin_id );
			$skin->duplicate_templates( $source_skin_id );
		} else {
			$templates = [];

			$skin = new Thrive_Skin( $skin_id );

			$layout_id = static::create_default_layout( $skin );

			foreach ( static::templates_meta() as $meta ) {
				$meta['meta_input']['layout'] = $layout_id;

				$templates[] = Thrive_Template::default_values( $meta );
			}

			foreach ( $templates as $data ) {
				$template_id = wp_insert_post( $data );

				$template = new Thrive_Template( $template_id );

				$template->update(
					[
						'style' => static::template_default_styles( $template ),
						'tag'   => uniqid(),
					]
				);
				$template->assign_to_skin( $skin_id );
			}
		}
	}

	/**
	 * Default values for header/footer
	 *
	 * @param $type
	 *
	 * @return array
	 */
	public static function default_symbol_values( $type ) {
		return [
			'id'      => 0,
			'hide'    => 0,
			'content' => Thrive_Utils::return_part( '/inc/templates/default/' . $type . '.php' ),
		];
	}

	/**
	 * Create a default/empty layout with nothing
	 *
	 * @param $skin Thrive_Skin
	 *
	 * @return int|WP_Error
	 */
	public static function create_default_layout( $skin ) {
		$layout_id = wp_insert_post( [
			'post_title'  => 'Boxed Layout',
			'post_status' => 'publish',
			'post_type'   => THRIVE_LAYOUT,
			'meta_input'  => array_merge( Thrive_Layout::$meta_fields, [ 'default' => 1 ] ),
		] );

		$skin->set_meta( Thrive_Skin::DEFAULT_LAYOUT, $layout_id );

		/* assign layout to skin */
		wp_set_object_terms( $layout_id, $skin->ID, SKIN_TAXONOMY );

		return $layout_id;
	}

	/**
	 * Default templates that should be created at the beginning.
	 *
	 * @return array
	 */
	public static function templates_meta() {
		return [
			[
				'meta_input' => [
					THRIVE_PRIMARY_TEMPLATE   => THRIVE_SINGULAR_TEMPLATE,
					THRIVE_SECONDARY_TEMPLATE => THRIVE_POST_TEMPLATE,
					'default'                 => 1,
				],
				'post_title' => __( 'Standard Post', THEME_DOMAIN ),
				'format'     => THRIVE_STANDARD_POST_FORMAT,
			],
			[
				'meta_input' => [
					THRIVE_PRIMARY_TEMPLATE   => THRIVE_SINGULAR_TEMPLATE,
					THRIVE_SECONDARY_TEMPLATE => THRIVE_PAGE_TEMPLATE,
					'default'                 => 1,
				],
				'post_title' => __( 'Page', THEME_DOMAIN ),
			],
			[
				'meta_input' => [
					THRIVE_PRIMARY_TEMPLATE => THRIVE_ARCHIVE_TEMPLATE,
					'default'               => 1,
				],
				'post_title' => __( 'All Archives', THEME_DOMAIN ),
			],
			[
				'meta_input' => [
					THRIVE_PRIMARY_TEMPLATE   => THRIVE_HOMEPAGE_TEMPLATE,
					THRIVE_SECONDARY_TEMPLATE => THRIVE_BLOG_TEMPLATE,
					'default'                 => 1,
				],
				'post_title' => __( 'Blog', THEME_DOMAIN ),
			],
		];
	}

	/**
	 * @param Thrive_Template $template
	 *
	 * @return array
	 */
	public static function template_default_styles( $template = null ) {
		$style = [];

		if ( $template === null ) {
			$template = thrive_template();
		}

		if ( $template !== null ) {
			$style = Thrive_Defaults::template_styles( $template->body_class( false, 'string' ) );
		}

		return $style;
	}
}
