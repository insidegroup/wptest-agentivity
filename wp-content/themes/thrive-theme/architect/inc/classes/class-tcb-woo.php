<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class TCB_Woo
 */
class TCB_Woo {
	const META_SHORTCODE = 'thrive_woo_meta_shortcode';

	const LINK_SHORTCODE = 'thrive_woo_link_shortcode';

	const POST_TYPE = 'product';

	/**
	 * Used as a proxy for calling woo functions only when the plugin is active
	 *
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic( $name, $arguments ) {

		if ( method_exists( __CLASS__, $name ) ) {
			return call_user_func_array( array( __CLASS__, $name ), $arguments );
		}

		if ( function_exists( $name ) && static::active() ) {
			/* dynamic call woocommerce functions if they exist */
			return call_user_func_array( $name, $arguments );
		}

		return false;
	}

	/**
	 * Add theme support for WooCommerce
	 */
	public static function init() {
		if ( static::active() ) {

			add_shortcode( static::META_SHORTCODE, array( __CLASS__, 'render_meta_shortcode' ) );
			add_shortcode( static::LINK_SHORTCODE, array( __CLASS__, 'render_link_shortcode' ) );

			add_filter( 'tcb_inline_shortcodes', array( __CLASS__, 'inline_shortcodes_filter' ) );

			add_filter( 'tcb_dynamiclink_data', array( __CLASS__, 'dynamiclink_data_filter' ) );

			add_filter( 'tcb_content_allowed_shortcodes', array( __CLASS__, 'content_allowed_shortcodes_filter' ) );

			add_filter( 'tcb_post_list_post_info', array( __CLASS__, 'shortcode_real_data' ), 10, 2 );
		}
	}

	/**
	 * Add woo inline shortcodes
	 *
	 * @param $shortcodes
	 *
	 * @return array
	 */
	public static function inline_shortcodes_filter( $shortcodes ) {
		return array_merge_recursive( $shortcodes, static::get_inline_shortcodes() );
	}

	/**
	 * Add dynamic woo links
	 *
	 * @param $dynamic_links
	 *
	 * @return array
	 */
	public static function dynamiclink_data_filter( $dynamic_links ) {
		return array_merge_recursive( $dynamic_links, static::get_dynamic_links() );
	}

	/**
	 * When editing a landing page, allow woo shortcodes to render
	 *
	 * @param $shortcodes
	 *
	 * @return array
	 */
	public static function content_allowed_shortcodes_filter( $shortcodes ) {
		if ( tve_post_is_landing_page() && is_editor_page() ) {
			$shortcodes[] = static::META_SHORTCODE;
			$shortcodes[] = static::LINK_SHORTCODE;
		}

		return $shortcodes;
	}

	/**
	 * Check if WooCommerce is active
	 * @return bool
	 */
	public static function active() {
		return class_exists( 'WooCommerce', false );
	}

	/**
	 * Render inline shortcodes - stored into the meta of the current product
	 *
	 * @param $attr
	 *
	 * @return mixed|string
	 */
	public static function render_meta_shortcode( $attr ) {

		$attr = shortcode_atts( array(
			'id' => '',
		), $attr );

		$content = '';

		if ( array_key_exists( $attr['id'], static::available_shortcodes() ) ) {
			$content = get_post_meta( get_the_ID(), $attr['id'], true );
		}

		return $content;
	}

	/**
	 * Render shortcodes for dynamic links
	 *
	 * @param $attr
	 *
	 * @return mixed|string
	 */
	public static function render_link_shortcode( $attr ) {

		$attr = shortcode_atts( array(
			'id' => '',
		), $attr );

		switch ( $attr['id'] ) {
			case 'add_to_cart':
				$link = wc_get_cart_url() . '?add-to-cart=' . get_the_ID();
				break;
			case 'cart_url':
				$link = wc_get_cart_url();
				break;
			case 'shop_url':
				$link = static::get_shop_url();
				break;
			default:
				$link = '#';
		}

		return $link;
	}

	/**
	 * Dynamic links available in the editor
	 * @return array
	 */
	public static function get_dynamic_links() {
		return array(
			'WooCommerce' => array(
				'links'     => array(
					array(
						array(
							'name'  => __( 'Add To Cart', 'thrive-cb' ),
							'label' => __( 'Add To Cart', 'thrive-cb' ),
							'url'   => '',
							'show'  => true,
							'id'    => 'add_to_cart',
						),
						array(
							'name'  => __( 'Cart Page', 'thrive-cb' ),
							'label' => __( 'Cart Page', 'thrive-cb' ),
							'url'   => '',
							'show'  => true,
							'id'    => 'cart_url',
						),
						array(
							'name'  => __( 'Shop Page', 'thrive-cb' ),
							'label' => __( 'Shop Page', 'thrive-cb' ),
							'url'   => '',
							'show'  => true,
							'id'    => 'shop_url',
						),
					),
				),
				'shortcode' => static::LINK_SHORTCODE,
			),
		);
	}

	/**
	 * Shortcodes implemented for WooCommerce
	 * @return mixed
	 */
	public static function get_inline_shortcodes() {
		$shortcodes['WooCommerce'] = array();

		foreach ( static::available_shortcodes() as $shortcode => $name ) {
			$shortcodes['WooCommerce'][] = array(
				'name'        => $name,
				'option'      => $name,
				'value'       => static::META_SHORTCODE,
				'extra_param' => $shortcode,
				'input'       => array(
					'id' => array(
						'extra_options' => array(),
						'real_data'     => $name,
						'type'          => 'hidden',
						'value'         => $shortcode,
					),
				),
			);
		}

		return $shortcodes;
	}

	/**
	 * Currently available inline shortcodes
	 * @return array
	 */
	public static function available_shortcodes() {
		return array(
			'_sale_price'        => __( 'Sale Price', 'thrive-cb' ),
			'_regular_price'     => __( 'Regular Price', 'thrive-cb' ),
			'_wc_average_rating' => __( 'Average Rating', 'thrive-cb' ),
		);

	}

	/**
	 * Add extra info for products needed for inline shortcodes
	 *
	 * @param $post_info
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public static function shortcode_real_data( $post_info, $post_id ) {

		if ( get_post_type( $post_id ) === 'product' ) {
			foreach ( array_keys( static::available_shortcodes() ) as $shortcode ) {
				$post_info[ static::META_SHORTCODE ][ $shortcode ] = get_post_meta( $post_id, $shortcode, true );
			}
		}

		return $post_info;
	}

	/**
	 * Return woocommerce shop url
	 * @return mixed
	 */
	public static function get_shop_url() {
		return wc_get_page_permalink( 'shop' );
	}
}
