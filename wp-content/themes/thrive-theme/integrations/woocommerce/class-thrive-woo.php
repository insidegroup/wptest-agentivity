<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

if ( ! class_exists( 'TCB_Woo', false ) ) {
	require_once THEME_PATH . '/architect/inc/classes/class-tcb-woo.php';
}

/**
 * Class Thrive_Woo
 */
class Thrive_Woo extends TCB_Woo {

	/**
	 * Return the title of the page where the shop is set.
	 * @return string
	 */
	public static function get_shop_title() {
		return get_the_title( static::wc_get_page_id( 'shop' ) );
	}

	/**
	 * Check if we're on the admin edit page of the shop
	 * @return bool
	 */
	public static function is_admin_shop_page() {
		$is_shop = false;

		if ( parent::active() && wc_get_page_id( 'shop' ) === get_the_ID() ) {
			$is_shop = true;
		}

		return $is_shop;
	}
}
