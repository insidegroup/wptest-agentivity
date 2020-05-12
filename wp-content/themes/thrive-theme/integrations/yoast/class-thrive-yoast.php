<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}
use Yoast\WP\SEO\Memoizers\Meta_Tags_Context_Memoizer;

/**
 * Class Thrive_Yoast
 */
class Thrive_Yoast {

	/**
	 * Use general singleton methods
	 */
	use Thrive_Singleton;

	/**
	 * Check if Yoast is active
	 *
	 * @return bool
	 */
	public function active() {
		return is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' );
	}

	/**
	 * Get meta description from yoast
	 * We are using logic from YoastSEO because it handles internally all the cases ( post, page, archives, etc. )
	 *
	 * @return string
	 */
	public function get_meta_description() {
		$meta_description = '';

		if ( function_exists( 'YoastSEO' ) ) {
			$context_memoizer = YoastSEO()->classes->get( Meta_Tags_Context_Memoizer::class );
			$context          = $context_memoizer->for_current_page();
			$presentation     = apply_filters( 'wpseo_frontend_presentation', $context->presentation, $context );

			$meta_description = $presentation->meta_description;
		}

		return $meta_description;
	}
}

/**
 * Return Thrive_Yoast instance
 *
 * @return Thrive_Yoast
 */
function thrive_yoast() {
	return Thrive_Yoast::instance();
}
