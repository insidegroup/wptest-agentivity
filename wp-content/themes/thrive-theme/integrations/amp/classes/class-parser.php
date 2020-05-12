<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

namespace Thrive\Theme\AMP;

use Thrive_DOM_Helper as DOM_Helper;

use DOMDocument;
use DOMElement;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Parser
 * @package Thrive\Theme\AMP
 */
class Parser {

	const PARSER_PATH = Main::AMP_PATH . 'classes/parsers/';

	const EARLY_PARSE_HOOK = 'thrive_theme_amp_early_parsed_content';
	const PARSE_HOOK = 'thrive_theme_amp_parsed_content';

	const PARSER_NAMESPACE = 'Parsers';

	/* we delete the elements that have these classes */
	const BLACKLISTED_CLASSES = [
		'tcb-pagination',
		'thrv_social',
		'thrv_lead_generation',
		'thrv-search-form',
		'thrv-contact-form',
		'thrv-pricing-table',
		'thrv_tabs_shortcode',
		'thrv-tabbed-content',
		'thrv_toggle'
	];

	/* we delete the elements that have these tags */
	const BLACKLISTED_TAGS = [
		'style'
	];

	/* if we find these classes, we have to add 'width:100%' to them because they usually have fixed widths in their style attribute */
	const RESPONSIVE_TARGET_CLASSES = [
		'tcb-window-width',
	];

	/* replace/remove these in the content ( position 0 from 'find' is replaced with position 0 from 'replace' and so on ) */
	const REPLACE_IN_CONTENT = [
		'find'    => [
			'!important',
			'javascript:void(0)',
			'--tcb-applied-color',
			'--tve-font-size',
		],
		'replace' => [
			'',
			'',
			'color',
			'font-size',
		],
	];

	/* will be extended soon (todo: see eliminate_invalid_attributes() ) */
	const CLIPPATH_FORBIDDEN_ATTR = [
		'decoration-type',
		'pointer-height',
		'pointer-width',
		'slanted-angle'
	];

	/*
	 * Initialize the element parsers - they should hook into the parsing hook in order to modify the content
	 */
	public static function init() {

		$dir = static::PARSER_PATH;

		/* iterate through the parser folder and include each file */
		foreach ( scandir( $dir ) as $file ) {
			if ( in_array( $file, [ '.', '..' ] ) ) {
				continue;
			}

			require_once $dir . $file;

			/* for each file, dynamically call the init function of the class */
			if ( preg_match( '/class-(.*).php/m', $file, $m ) && ! empty( $m[1] ) ) {
				$element = ucfirst( $m[1] );
				$class   = __NAMESPACE__ . '\\' . static::PARSER_NAMESPACE . '\\' . $element;

				if ( method_exists( $class, 'init' ) ) {
					$class::init();
				}
			}
		}
	}

	/**
	 * Check the content for invalid elements, then modify some elements so they are compatible with AMP.
	 *
	 * @param string $content
	 *
	 * @return string $content
	 */
	public static function parse_content( $content ) {
		if ( empty( $content ) ) {
			return '';
		}

		/* @var DOMDocument $dom */
		$dom = DOM_Helper::initialize_dom_document( $content );

		if ( $dom ) {
			/**
			 * Let the element parsers know that they can do their own early parsing on the DOMDocument
			 *
			 * @param DOMDocument $dom
			 */
			do_action( static::EARLY_PARSE_HOOK, $dom );

			static::parse_comments( $dom );

			static::eliminate_invalid_elements( $dom );
			static::eliminate_invalid_attributes( $dom );

			static::fix_responsiveness( $dom );

			/* this relies on <img> tags still existing, it has to be done before the parse action */
			static::parse_logos( $dom );

			/**
			 * Let the element parsers know that they can do their own parsing on the DOMDocument
			 *
			 * @param DOMDocument $dom
			 */
			do_action( static::PARSE_HOOK, $dom );

			static::parse_svgs( $dom );

			$content = DOM_Helper::get_content_from_dom( $dom );

			$content = str_replace( static::REPLACE_IN_CONTENT['find'], static::REPLACE_IN_CONTENT['replace'], $content );
		}

		return $content;
	}

	/**
	 * Iterate through some target classes and apply 'width:100%' to them
	 * The reason is that these classes have fixed widths that are not responsive.
	 *
	 * @param DOMDocument $dom
	 */
	public static function fix_responsiveness( $dom ) {
		foreach ( $dom->getElementsByTagName( 'div' ) as $node ) {
			/* @var DOMElement $node */
			foreach ( static::RESPONSIVE_TARGET_CLASSES as $class ) {
				if ( DOM_Helper::has_class( $node, $class ) ) {
					$node->setAttribute( 'style', 'width:100%' );
				}
			}
		}
	}

	/**
	 * Hide some stuff that we're not ready to display yet, such as post lists, social shares, etc
	 * Also eliminate incompatible HTML tags
	 *
	 * @param DOMDocument $dom
	 */
	public static function eliminate_invalid_elements( $dom ) {
		$nodes_to_delete = [];

		foreach ( $dom->getElementsByTagName( 'div' ) as $node ) {
			/* @var DOMElement $node */
			foreach ( static::BLACKLISTED_CLASSES as $class ) {
				if ( DOM_Helper::has_class( $node, $class ) ) {
					$nodes_to_delete[] = $node;
					break;
				}
			}
		}

		foreach ( static::BLACKLISTED_TAGS as $tag ) {
			foreach ( $dom->getElementsByTagName( $tag ) as $node ) {
				/* @var DOMElement $node */
				$nodes_to_delete[] = $node;
			}
		}

		foreach ( $nodes_to_delete as $node_to_delete ) {
			DOM_Helper::delete_node( $node_to_delete );
		}
	}

	/**
	 * Remove invalid attributes ( todo: expand this when more cases appear, maybe merge with parse_svgs() because they do similar things )
	 *
	 * @param DOMDocument $dom
	 */
	public static function eliminate_invalid_attributes( $dom ) {
		$link_nodes = $dom->getElementsByTagName( 'a' );

		foreach ( $link_nodes as $link_node ) {
			/* @var $link_node DOMElement */
			$link_node->removeAttribute( 'dynamic-postlink' );
			$link_node->removeAttribute( 'jump-animation' );
			/* when one more appears, solve the todo from above */
		}

		$div_nodes = $dom->getElementsByTagName( 'div' );

		foreach ( $div_nodes as $div_node ) {
			/* @var $div_node DOMElement */
			$div_node->removeAttribute( 'tcb-template-id' );
			$div_node->removeAttribute( 'tcb-template-name' );
			$div_node->removeAttribute( 'tcb-template-pack' );
		}
	}

	/**
	 * Remove a few forbidden attributes from SVGs and clippaths
	 *
	 * @param DOMDocument $dom
	 */
	public static function parse_svgs( $dom ) {
		foreach ( $dom->getElementsByTagName( 'svg' ) as $svg_node ) {
			/* @var DOMElement $svg_node */
			$svg_node->removeAttribute( 'decoration-type' );

			foreach ( $svg_node->getElementsByTagName( 'clippath' ) as $clip_path_node ) {
				/* @var DOMElement $clip_path_node */
				foreach ( static::CLIPPATH_FORBIDDEN_ATTR as $forbidden_attr ) {
					$clip_path_node->removeAttribute( $forbidden_attr );
				}
			}
		}
	}

	/**
	 * Replace the comments section with a button that links to the comments form on the non-amp page
	 *
	 * @param DOMDocument $dom
	 */
	public static function parse_comments( $dom ) {
		$comments_nodes = DOM_Helper::get_all_nodes_for_tag_and_class( 'div', 'comments-area', $dom );

		if ( ! empty( $comments_nodes ) ) {
			$comments_node = $comments_nodes[0];

			$post_id = get_the_ID();

			/* generate a link without 'amp' in it */
			$GLOBALS[ Main::GENERATE_AMP_PERMALINK_KEY ] = false;

			$link = get_comments_link( $post_id );

			$GLOBALS[ Main::GENERATE_AMP_PERMALINK_KEY ] = true;

			$comments_link = Main::get_amp_file( 'templates/comments-link.php', [
				'link' => $link,
				'text' => esc_html__( comments_open( $post_id ) ? 'Leave a Comment' : 'View Comments', THEME_DOMAIN ),
			] );

			DOM_Helper::replace_node_with_string( $comments_node, $comments_link, $dom );
		}
	}

	/**
	 * The <picture> tag used by the logo is not AMP-compatible.
	 * In order to 'fix' it, we replace it with the fallback <img> tag that we already have inside the picture element.
	 *
	 * @param DOMDocument $dom
	 */
	public static function parse_logos( $dom ) {
		foreach ( $dom->getElementsByTagName( 'picture' ) as $picture_node ) {
			/* @var DOMElement $picture_node */
			$logo_wrapper = $picture_node->parentNode;

			/* make sure the picture tag is a child of the logo wrapper */
			if ( strpos( $logo_wrapper->getAttribute( 'class' ), 'tcb-logo' ) !== false ) {
				/* replace <picture> with the image fallback that's inside it */
				$logo_wrapper->replaceChild( $picture_node->getElementsByTagName( 'img' )->item( 0 ), $picture_node );
			}
		}
	}
}
