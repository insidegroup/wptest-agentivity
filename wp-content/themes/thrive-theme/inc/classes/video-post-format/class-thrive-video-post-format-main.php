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
 * Class Thrive_Video_Post_Format_Main
 */
class Thrive_Video_Post_Format_Main {

	const VIDEO_META_PREFIX = 'thrive_meta_postformat_video';
	const VIDEO_META_TYPE_PREFIX = 'thrive_meta_postformat_video_type';
	const VIDEO_META_OPTION = 'thrive_theme_video_format_meta';

	const CUSTOM = 'custom';
	const VIMEO = 'vimeo';
	const WISTIA = 'wistia';
	const YOUTUBE = 'youtube';

	const ALL_VIDEO_TYPES = [ self::YOUTUBE, self::VIMEO, self::WISTIA, self::CUSTOM ];

	/**
	 * Get the video type.
	 *
	 * @return array|mixed
	 */
	public static function get_type() {
		$post_id = get_the_ID();
		$options = get_post_meta( $post_id, static::VIDEO_META_OPTION, true );

		if ( empty( $options ) ) {
			/* if the new options are empty, look for options from the old themes */
			$old_theme_type_key = '_' . static::VIDEO_META_TYPE_PREFIX;
			$type               = get_post_meta( $post_id, $old_theme_type_key, true );
		} elseif ( ! empty( $options['type'] ) ) {
			$type = $options['type'];
		}

		/* if nothing is found, use youtube as a default value */
		$type = empty( $type ) ? static::YOUTUBE : $type;

		return $type;
	}

	/*
	 * Called by the 'save_post' action. Saves the video post meta settings.
	 */
	public static function save_video_meta_fields() {
		if ( empty( get_the_ID() ) ) {
			return;
		}

		/* if type is not set, something is terribly wrong */
		if ( isset( $_POST[ static::VIDEO_META_TYPE_PREFIX ] ) ) {
			$type = $_POST[ static::VIDEO_META_TYPE_PREFIX ];

			$post_format = thrive_video_post_format( $type );

			if ( ! empty( $post_format ) ) {
				$post_format->save_options( $_POST );
			}
		}
	}

	/**
	 * Render the thumbnail and the iframe separately.
	 *
	 * @param $attr
	 *
	 * @return mixed
	 */
	public static function render( $attr = [] ) {
		$classes = [ 'tve_responsive_video_container' ];

		/* check if we should hide this element from the page ( by returning nothing or by adding classes to hide it ) */
		if ( ! thrive_post()->is_element_visible( 'featured_video', $classes ) ) {
			return '';
		}

		$thumbnail = static::render_thumbnail( $attr );

		/* forward the render job to the class with the current video type */
		$iframe    = thrive_video_post_format( static::get_type() )->render( static::has_thumbnail( $attr ) );
		$container = $thumbnail . $iframe;

		/*If the video is floating, the fhumbnail should be wrapped inside the floating container*/
		if ( ! empty( $attr['is-floating'] ) ) {
			$container = TCB_Utils::wrap_content( $container, 'div', '', 'tcb-video-float-container' );
		}

		return $iframe ? TCB_Utils::wrap_content( $container, 'div', '', $classes, Thrive_Utils::create_attributes( $attr ) ) : '';
	}

	/**
	 * Generate the video thumbnail. If there is a thumbnail url, use an image, otherwise use a placeholder.
	 *
	 * @param $attr
	 *
	 * @return string
	 */
	public static function render_thumbnail( $attr ) {
		$thumbnail_type = isset( $attr['thumbnail-type'] ) ? $attr['thumbnail-type'] : 'none';

		/* for dynamic thumbnail, use the featured image url shortcode as image background url */
		if ( $thumbnail_type === 'dynamic' ) {
			$thumbnail_url = Thrive_Shortcodes::the_post_thumbnail_url();
		} elseif ( $thumbnail_type === 'static' && ! empty( $attr['thumbnail-url'] ) ) {
			/* else, use the url that came through the shortcode */
			$thumbnail_url = $attr['thumbnail-url'];
		}

		/* if the URL is empty, use a placeholder in the editor and nothing on the frontend */
		if ( empty( $thumbnail_url ) ) {
			if ( Thrive_Utils::is_inner_frame() ) {
				$thumbnail = static::render_placeholder();
			} else {
				$thumbnail = TCB_Utils::wrap_content( '', 'div', 'video-overlay' );
			}
		} else {
			$thumbnail = Thrive_Utils::return_part( '/inc/templates/parts/dynamic-video-overlay.php', [ 'thumbnail-url' => $thumbnail_url ] );
		}

		return $thumbnail;
	}

	/**
	 * Return true if the video has a thumbnail, false if it doesn't.
	 *
	 * @param $attr
	 *
	 * @return bool
	 */
	public static function has_thumbnail( $attr ) {
		$thumbnail_type = isset( $attr['thumbnail-type'] ) ? $attr['thumbnail-type'] : 'none';

		/* the video has a thumbnail if the thumbnail type is dynamic or if it's static and there is an url */

		return $thumbnail_type === 'dynamic' || ( $thumbnail_type === 'static' && ! empty( $attr['thumbnail-url'] ) );
	}

	public static function render_placeholder() {
		if ( is_editor_page_raw( true ) ) {
			$content = Thrive_Utils::return_part( '/inc/templates/parts/dynamic-video-placeholder.php' );
		} else {
			$content = '';
		}

		return $content;
	}
}
