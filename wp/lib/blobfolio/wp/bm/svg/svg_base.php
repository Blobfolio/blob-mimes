<?php
/**
 * Lord of the Files - SVG base class
 *
 * This is extended by either DOMDocument or the fallback.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm\svg;

use \blobfolio\wp\bm\mb;

abstract class svg_base {

	const XMLNS_NAMESPACE = 'http://www.w3.org/2000/svg';
	const XMLTAG = '<?xml version="1.0" encoding="utf-8" ?>';

	/**
	 * Return SVG DOCTYPE
	 *
	 * Defaults to 1.1 spec.
	 *
	 * @see { https://www.w3.org/TR/SVG/ }
	 *
	 * @return string DOCTYPE.
	 */
	public static function get_doctype() {
		$doctype = '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">';

		$doctype = apply_filters('blobmimes_svg_doctype', $doctype);
		return is_string($doctype) ? trim($doctype) : '';
	}


	/**
	 * Return whitelisted tags
	 *
	 * Based on SVG spec, minus anything scripty.
	 *
	 * @see { https://developer.mozilla.org/en-US/docs/Web/SVG/Element }
	 *
	 * @return array Array of valid tags.
	 */
	public static function get_allowed_tags() {
		$tags = array(
			'a',
			'altGlyph',
			'altGlyphDef',
			'altGlyphItem',
			'animate',
			'animateColor',
			'animateMotion',
			'animateTransform',
			'audio',
			'canvas',
			'circle',
			'clipPath',
			'color-profile',
			'cursor',
			'defs',
			'desc',
			'discard',
			'ellipse',
			'feBlend',
			'feColorMatrix',
			'feComponentTransfer',
			'feComposite',
			'feConvolveMatrix',
			'feDiffuseLighting',
			'feDisplacementMap',
			'feDistantLight',
			'feDropShadow',
			'feFlood',
			'feFuncA',
			'feFuncB',
			'feFuncG',
			'feFuncR',
			'feGaussianBlur',
			'feImage',
			'feMerge',
			'feMergeNode',
			'feMorphology',
			'feOffset',
			'fePointLight',
			'feSpecularLighting',
			'feSpotLight',
			'feTile',
			'feTurbulence',
			'filter',
			'font',
			'font-face',
			'font-face-format',
			'font-face-name',
			'font-face-src',
			'font-face-uri',
			'g',
			'glyph',
			'glyphRef',
			'hatch',
			'hatchpath',
			'hkern',
			'image',
			'line',
			'linearGradient',
			'marker',
			'mask',
			'mesh',
			'meshgradient',
			'meshpatch',
			'meshrow',
			'metadata',
			'missing-glyph',
			'mpath',
			'path',
			'pattern',
			'polygon',
			'polyline',
			'radialGradient',
			'rect',
			'set',
			'solidcolor',
			'stop',
			'style',
			'svg',
			'switch',
			'symbol',
			'text',
			'textPath',
			'title',
			'tref',
			'tspan',
			'unknown',
			'use',
			'video',
			'view',
			'vkern',
		);

		$tags = apply_filters('blobmimes_svg_allowed_tags', $tags);
		if (!is_array($tags) || !count($tags)) {
			return array();
		}

		// Convert to lowercase for easier comparison.
		foreach ($tags as $k=>$v) {
			$tags[$k] = mb::strtolower(trim($v));
			if (!strlen($tags[$k])) {
				unset($tags[$k]);
			}
		}

		// Pre-sorting will speed up looped checks.
		$tags = array_unique($tags);
		sort($tags);
		return $tags;
	}


	/**
	 * Return whitelisted attributes
	 *
	 * Based on SVG spec, minus anything scripty.
	 *
	 * @see { https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute }
	 *
	 * @return array Array of valid attributes.
	 */
	public static function get_allowed_attributes() {
		$attributes = array(
			'accent-height',
			'accumulate',
			'additive',
			'alignment-baseline',
			'allowReorder',
			'alphabetic',
			'amplitude',
			'arabic-form',
			'ascent',
			'attributeName',
			'attributeType',
			'autoReverse',
			'azimuth',
			'baseFrequency',
			'baseline-shift',
			'baseProfile',
			'bbox',
			'begin',
			'bias',
			'by',
			'calcMode',
			'cap-height',
			'class',
			'clip',
			'clipPathUnits',
			'clip-path',
			'clip-rule',
			'color',
			'color-interpolation',
			'color-interpolation-filters',
			'color-profile',
			'color-rendering',
			'contentStyleType',
			'cursor',
			'cx',
			'cy',
			'd',
			'decelerate',
			'descent',
			'diffuseConstant',
			'direction',
			'display',
			'divisor',
			'dominant-baseline',
			'dur',
			'dx',
			'dy',
			'edgeMode',
			'elevation',
			'enable-background',
			'end',
			'exponent',
			'externalResourcesRequired',
			'fill',
			'fill-opacity',
			'fill-rule',
			'filter',
			'filterRes',
			'filterUnits',
			'flood-color',
			'flood-opacity',
			'font-family',
			'font-size',
			'font-size-adjust',
			'font-stretch',
			'font-style',
			'font-variant',
			'font-weight',
			'format',
			'from',
			'fx',
			'fy',
			'g1',
			'g2',
			'glyph-name',
			'glyph-orientation-horizontal',
			'glyph-orientation-vertical',
			'glyphRef',
			'gradientTransform',
			'gradientUnits',
			'hanging',
			'height',
			'href',
			'horiz-adv-x',
			'horiz-origin-x',
			'id',
			'ideographic',
			'image-rendering',
			'in',
			'in2',
			'intercept',
			'k',
			'k1',
			'k2',
			'k3',
			'k4',
			'kernelMatrix',
			'kernelUnitLength',
			'kerning',
			'keyPoints',
			'keySplines',
			'keyTimes',
			'lang',
			'lengthAdjust',
			'letter-spacing',
			'lighting-color',
			'limitingConeAngle',
			'local',
			'marker-end',
			'marker-mid',
			'marker-start',
			'markerHeight',
			'markerUnits',
			'markerWidth',
			'mask',
			'maskContentUnits',
			'maskUnits',
			'mathematical',
			'max',
			'media',
			'method',
			'min',
			'mode',
			'name',
			'numOctaves',
			'offset',
			'opacity',
			'operator',
			'order',
			'orient',
			'orientation',
			'origin',
			'overflow',
			'overline-position',
			'overline-thickness',
			'panose-1',
			'paint-order',
			'pathLength',
			'patternContentUnits',
			'patternTransform',
			'patternUnits',
			'pointer-events',
			'points',
			'pointsAtX',
			'pointsAtY',
			'pointsAtZ',
			'preserveAlpha',
			'preserveAspectRatio',
			'primitiveUnits',
			'r',
			'radius',
			'refX',
			'refY',
			'rendering-intent',
			'repeatCount',
			'repeatDur',
			'requiredExtensions',
			'requiredFeatures',
			'restart',
			'result',
			'rotate',
			'rx',
			'ry',
			'scale',
			'seed',
			'shape-rendering',
			'slope',
			'spacing',
			'specularConstant',
			'specularExponent',
			'speed',
			'spreadMethod',
			'startOffset',
			'stdDeviation',
			'stemh',
			'stemv',
			'stitchTiles',
			'stop-color',
			'stop-opacity',
			'strikethrough-position',
			'strikethrough-thickness',
			'string',
			'stroke',
			'stroke-dasharray',
			'stroke-dashoffset',
			'stroke-linecap',
			'stroke-linejoin',
			'stroke-miterlimit',
			'stroke-opacity',
			'stroke-width',
			'style',
			'surfaceScale',
			'systemLanguage',
			'tabindex',
			'tableValues',
			'target',
			'targetX',
			'targetY',
			'text-anchor',
			'text-decoration',
			'text-rendering',
			'textLength',
			'to',
			'transform',
			'type',
			'u1',
			'u2',
			'underline-position',
			'underline-thickness',
			'unicode',
			'unicode-bidi',
			'unicode-range',
			'units-per-em',
			'v-alphabetic',
			'v-hanging',
			'v-ideographic',
			'v-mathematical',
			'values',
			'version',
			'vert-adv-y',
			'vert-origin-x',
			'vert-origin-y',
			'viewBox',
			'viewTarget',
			'visibility',
			'width',
			'widths',
			'word-spacing',
			'writing-mode',
			'x',
			'x-height',
			'x1',
			'x2',
			'xChannelSelector',
			'xlink:actuate',
			'xlink:arcrole',
			'xlink:href',
			'xlink:role',
			'xlink:show',
			'xlink:title',
			'xlink:type',
			'xml:base',
			'xml:lang',
			'xml:space',
			'xmlns',
			'xmlns:xlink',
			'xmlns:xml',
			'y',
			'y1',
			'y2',
			'yChannelSelector',
			'z',
			'zoomAndPan',
		);

		$attributes = apply_filters('blobmimes_svg_allowed_attributes', $attributes);
		if (!is_array($attributes) || !count($attributes)) {
			return array();
		}

		// Convert to lowercase for easier comparison.
		foreach ($attributes as $k=>$v) {
			$attributes[$k] = mb::strtolower(trim($v));
			if (!strlen($attributes[$k])) {
				unset($attributes[$k]);
			}
		}

		// Pre-sorting will speed up looped checks.
		$attributes = array_unique($attributes);
		sort($attributes);
		return $attributes;

	}


	/**
	 * Return IRI attributes
	 *
	 * @see { https://www.w3.org/TR/SVG/linking.html }
	 *
	 * @return array Array of possible attributes.
	 */
	public static function get_iri_attributes() {
		return array(
			'href',
			'src',
			'xlink:arcrole',
			'xlink:href',
			'xlink:role',
			'xml:base',
			'xmlns',
			'xmlns:xlink',
		);
	}


	/**
	 * Return whitelisted protocols
	 *
	 * @see { http://www.iana.org/assignments/uri-schemes/uri-schemes.xhtml }
	 *
	 * @return array Array of valid protocols.
	 */
	public static function get_allowed_protocols() {
		$protocols = array(
			'http',
			'https',
		);

		$protocols = apply_filters('blobmimes_svg_allowed_protocols', $protocols);
		if (!is_array($protocols) || !count($protocols)) {
			return array();
		}

		// Convert to lowercase for easier comparison.
		foreach ($protocols as $k=>$v) {
			$protocols[$k] = mb::strtolower(trim($v));
			if (!strlen($protocols[$k])) {
				unset($protocols[$k]);
			}
		}

		// Pre-sorting will speed up looped checks.
		$protocols = array_unique($protocols);
		sort($protocols);
		return $protocols;
	}


	/**
	 * Return whitelisted domains
	 *
	 * @return array Domains.
	 */
	public static function get_allowed_domains() {
		$domains = array(
			site_url(),
			'creativecommons.org',
			'inkscape.org',
			'sodipodi.sourceforge.net',
			'w3.org',
		);

		$domains = apply_filters('blobmimes_svg_allowed_domains', $domains);
		if (!is_array($domains) || !count($domains)) {
			return array();
		}

		// Sanitize the list before moving on.
		foreach ($domains as $k=>$domain) {
			if (false === ($domains[$k] = static::sanitize_domain($domain))) {
				unset($domains[$k]);
			}
		}

		// Pre-sorting will speed up looped checks.
		$domains = array_unique($domains);
		sort($domains);
		return $domains;
	}


	/**
	 * Sanitize a domain-like string for more
	 * level comparison.
	 *
	 * @param string $domain Domain.
	 * @return string|bool Domain. False on failure.
	 */
	public static function sanitize_domain($domain = '') {
		if (!is_string($domain)) {
			return false;
		}

		$domain = mb::strtolower(trim($domain));

		// Try to parse a full URL.
		$host = wp_parse_url($domain, PHP_URL_HOST);
		if (!$host) {
			// Strip path?
			if (false !== ($start = mb::strpos($domain, '/'))) {
				$domain = mb::substr($domain, 0, $start);
			}

			// Strip query?
			if (false !== ($start = mb::strpos($domain, '?'))) {
				$domain = mb::substr($domain, 0, $start);
			}

			// Strip port?
			if (
				!filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) &&
				false !== ($start = mb::strpos($domain, ':'))
			) {
				$domain = mb::substr($domain, 0, $start);
			}

			// Bail if invalid characters at this point.
			if (filter_var($domain, FILTER_SANITIZE_URL) !== $domain) {
				return false;
			}
		} else {
			$domain = $host;
		}

		if (is_string($domain)) {
			$domain = preg_replace('/^www\./u', '', $domain);
		}

		return $domain ? $domain : false;
	}


	/**
	 * Sanitize attribute value
	 *
	 * @param string $str Attribute value.
	 * @return string Attribute value.
	 */
	protected static function sanitize_attribute_value($str = '') {
		$str = wp_kses_no_null($str);

		// Decode all entities.
		$old_value = '';
		while ($old_value !== $str) {
			$old_value = $str;
			$str = wp_kses_decode_entities($str);
			$str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');

			// And (full) trim while we're here.
			$str = preg_replace('/^\s+/u', '', $str);
			$str = preg_replace('/\s+$/u', '', $str);
		}

		return $str;
	}


	/**
	 * Sanitize IRI value
	 *
	 * @param string $str IRI value.
	 * @return string IRI value.
	 */
	protected static function sanitize_iri_value($str = '') {
		$allowed_protocols = static::get_allowed_protocols();
		$allowed_domains = static::get_allowed_domains();

		if (wp_kses_bad_protocol($str, $allowed_protocols) !== $str) {
			return '';
		}

		// Assign a protocol.
		$str = preg_replace('/^\/\//', 'https://', $str);

		// Is this a URLish thing?
		if (filter_var($str, FILTER_SANITIZE_URL) !== $str) {
			return '';
		}

		// Check the domain, if applicable.
		if (preg_match('/^[\w\d]+:\/\//i', $str)) {
			if (false !== ($domain = static::sanitize_domain($str))) {
				if (!in_array($domain, $allowed_domains, true)) {
					return '';
				}
			} else {
				return '';
			}
		}

		return $str;
	}


	/**
	 * Callback handler for sanitizing CSS URLs
	 *
	 * @param array $match Match.
	 * @return string Sanitized URL.
	 */
	protected static function callback_sanitize_css_iri($match) {
		$str = static::sanitize_attribute_value($match[1]);

		// Strip quotes.
		$str = ltrim($str, "'\"");
		$str = rtrim($str, "'\"");

		$str = static::sanitize_iri_value($str);

		if (strlen($str)) {
			return "url('$str')";
		}

		return 'none';
	}


	/**
	 * Read an SVG file as contents or path.
	 *
	 * @param string $svg SVG code or path.
	 * @return string|bool SVG code. False on failure.
	 */
	protected static function load_svg($svg = '') {
		// Early bail.
		if (!is_string($svg) || !strlen($svg)) {
			return false;
		}

		// Maybe a path?
		if (
			false === mb::strpos(mb::strtolower($svg), '<svg') ||
			false === mb::strrpos(mb::strtolower($svg), '</svg>')
		) {
			try {
				// @codingStandardsIgnoreStart
				$svg = @file_get_contents($svg);
				// @codingStandardsIgnoreEnd
				if (!is_string($svg) || !strlen($svg)) {
					return false;
				}
			} catch (\Throwable $e) {
				return false;
			} catch (\Exception $e) {
				return false;
			}
		}

		// Lowercase the tags.
		$svg = preg_replace('/<svg/ui', '<svg', $svg);
		$svg = preg_replace('/<\/svg>/ui', '</svg>', $svg);

		if (
			false === ($start = mb::strpos($svg, '<svg')) ||
			false === ($end = mb::strrpos($svg, '</svg>'))
		) {
			return false;
		}
		$svg = mb::substr($svg, $start, ($end - $start + 6));

		// Remove comments.
		$svg = static::strip_comments($svg);

		return $svg;
	}

	/**
	 * Remove comments and interpreter tags from an SVG file
	 *
	 * @param string $svg SVG code or path.
	 * @return string|bool SVG code. False on failure.
	 */
	protected static function strip_comments($svg = '') {
		if (!is_string($svg)) {
			return false;
		}

		// Remove XML, PHP, ASP, etc.
		$svg = preg_replace('/<\?(.*)\?>/Us', '', $svg);
		$svg = preg_replace('/<\%(.*)\%>/Us', '', $svg);

		if (
			false !== mb::strpos($svg, '<?') ||
			false !== mb::strpos($svg, '<%')
		) {
			return false;
		}

		// Remove comments.
		$svg = preg_replace('/<!--(.*)-->/Us', '', $svg);
		$svg = preg_replace('/\/\*(.*)\*\//Us', '', $svg);

		if (
			false !== mb::strpos($svg, '<!--') ||
			false !== mb::strpos($svg, '/*')
		) {
			return false;
		}

		return $svg;
	}
}


