<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * This contains various functionality that addresses conflicts or incompatibilities with 3rd party products
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * CartFlows plugin removes ALL scripts and styles from the theme,
 * unless the current theme is not one of Divi or Flatsome ( it seems they hardcoded these )
 *
 * Fortunately, this can be controlled with a filter
 */
add_filter( 'cartflows_remove_theme_scripts', static function ( $remove_them ) {
	/* keep theme scripts on editor page */
	if ( is_editor_page_raw() ) {
		$remove_them = false;
	}

	return $remove_them;
} );

/**
 * In some cases we're rendering the template earlier and the Smart Slider 3 Plugin disables his slider shortcode.
 * We overwrite the shortcode and set it to normal mode so it can be rendered.
 */
if ( class_exists( N2SS3Shortcode::class, false ) ) {
	add_action( 'before_theme_builder_template_render', [ N2SS3Shortcode::class, 'shortcodeModeToNormal' ] );
}

/**
 * Compatibility with eLearnCommerce plugin - remove their template_include hooks so we can display our own templates.
 */
if ( class_exists( WPEP\Controller::class, false ) ) {
	add_action( 'wp', static function () {
		if ( has_action( 'template_include', [ WPEP\Controller::instance()->template, 'load' ] ) ) {
			if ( get_post_type() === 'courses' ) {
				add_action( 'wpep_before_main_content', static function () {
					echo '<div id="wrapper">' . thrive_template()->render_theme_hf_section( THRIVE_HEADER_SECTION ) . '<div id="content"><div class="main-container thrv_wrapper">';
				}, 1 );

				add_action( 'wpep_after_main_content', static function () {
					echo '</div></div>' . thrive_template()->render_theme_hf_section( THRIVE_FOOTER_SECTION ) . '</div>';
				}, PHP_INT_MAX );
			} else {
				remove_action( 'template_include', [ WPEP\Controller::instance()->template, 'load' ], 50 );
			}
		}
	} );
}

/**
 * Compatibility with relevanssi - when we render the blog list we need to let the plugin to do his search
 */
if ( function_exists( 'relevanssi_query' ) ) {
	add_action( 'theme_before_render_blog_list', static function () {
		global $relevanssi_active;
		$relevanssi_active = false;
	} );
}

/**
 * Compatibility with Optimole WP - image optimization plugin.
 * This makes sure the CSS style file is being generated with all the CSS background image URLs replaced with their optimole CDN equivalents
 */
if ( class_exists( 'Optml_Main', false ) ) {
	add_filter( 'thrive_css_file_content', static function ( $style ) {
		/* only replace if current request is a rest api ajax request ... */
		$should_replace_urls = ! empty( $_REQUEST['tar_editor_page'] ) && defined( 'REST_REQUEST' ) && REST_REQUEST;
		/* ... and it's the one that saves a Theme template  */
		$should_replace_urls = $should_replace_urls && ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'update_template';

		/* first, check to see if optimole is configured / connected */
		if ( $should_replace_urls && thrive_optimole_wp()->is_registered() ) {
			/* setup everything necessary. optimole does not allow force-registering their hooks on a custom request because of an incorrectly coded filter */
			do_action( 'optml_replacer_setup' );

			$style = Optml_Main::instance()->manager->process_urls_from_content( $style );
		}

		return $style;
	} );
}

/**
 * Compatibility with MemberPress
 * Their login page redirects to a list page, so we don't want that to be loaded inside our templates
 */
if ( class_exists( 'MeprOptions', false ) && method_exists( 'MeprOptions', 'fetch' ) ) {
	$mepr_options = MeprOptions::fetch();
	if ( ! empty( $mepr_options->login_page_id ) ) {
		add_filter( 'thrive_theme_get_posts_args', static function ( $args ) use ( $mepr_options ) {
			$args['exclude'][] = $mepr_options->login_page_id;

			return $args;
		} );
	}
}
add_filter( 'pre_site_option_loginpress_review_dismiss', function ( $value ) {
	/* If we are in the theme dashbaord dismiss the loginpress review */
	if ( Thrive_Utils::in_theme_dashboard() ) {
		$value = true;
	}

	return $value;
} );

add_filter( 'thrive_theme_show_meta', function ( $show ) {
	/** Check if we need to show the yoast meta description */
	$show_yoast_meta_description = class_exists( 'Thrive_Yoast', false ) && thrive_yoast()->active() && thrive_yoast()->get_meta_description() !== '';

	/** Check if we need to show the seo press meta description */
	$show_seo_press_meta_description = function_exists( 'seopress_titles_the_description_content' ) && seopress_titles_the_description_content() !== '';

	return ! $show_yoast_meta_description && ! $show_seo_press_meta_description && $show;
} );

add_filter( 'thrive_theme_ignore_post_types', function ( $post_types ) {

	/* Remove some memberpress custom post types for which there is no use case to create theme templates */
	$post_types[] = 'memberpressproduct';
	$post_types[] = 'memberpressgroup';

	return $post_types;
} );