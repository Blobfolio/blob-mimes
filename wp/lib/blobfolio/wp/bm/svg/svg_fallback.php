<?php
/**
 * Lord of the Files - SVG Fallback
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm\svg;

use blobfolio\wp\bm\mb;
use SimpleXMLElement;

class svg_fallback extends svg_base {

	/**
	 * Sanitize an SVG
	 *
	 * @param string $svg SVG code or path.
	 * @param bool $header Append header.
	 * @return string|bool Sanitized SVG code or false.
	 */
	public static function sanitize($svg = '', $header=true) {
		$svg = \apply_filters('blobmimes_svg_pre_sanitize', $svg);

		if (false === $svg = static::import($svg)) {
			return false;
		}

		// Allowed.
		$allowed_tags = static::get_allowed_tags();

		// We'll need to clean up styles a bit later.
		$style_tags = array();

		// First, find and remove invalid tags and their contents.
		\preg_match_all('%(<[^>]*(>|$)|>)%', $svg, $matches);
		if (\count($matches[0])) {
			$invalid = array();
			foreach ($matches[0] as $raw) {
				// Ignore malformed.
				if (
					\substr($raw, 0, 1) !== '<' ||
					! \preg_match('%^<\s*(/\s*)?([a-zA-Z0-9-:]+)([^>]*)>?$%', $raw, $matches2)
				) {
					continue;
				}

				// Don't need closing tags now.
				if (\trim($matches2[1]) === '/') {
					continue;
				}

				$tag = mb::strtolower($matches2[2]);
				$tag_name = $tag;
				// The tag might be namespaced (ns:tag). We'll allow it if
				// the tag is allowed.
				if (
					false !== mb::strpos($tag_name, ':') &&
					! \in_array($tag_name, $allowed_tags, true)
				) {
					$tag_name = \explode(':', $tag_name);
					$tag_name = $tag_name[1];
				}

				// Bad tag: not whitelisted.
				if (! \in_array($tag_name, $allowed_tags, true)) {
					$invalid[] = $tag;
				} elseif ('style' === $tag_name) {
					$style_tags[] = $tag;
				}
			}

			// Actual removal.
			if (\count($invalid)) {
				$invalid = \array_unique($invalid);
				foreach ($invalid as $k=>$v) {
					$invalid[$k] = \preg_quote($v, '@');
				}

				// Remove open/close pairs.
				$svg = \preg_replace('@<(' . \implode('|', $invalid) . ')\b.*?>.*?</\1>@si', '', $svg);

				// Remove self-closing.
				$svg = \preg_replace('@<(' . \implode('|', $invalid) . ')\b.*?/>@si', '', $svg);
			}
		}

		// Similar to wp_kses_split(), however a separate
		// function was needed for proper XML support.
		$svg = \preg_replace_callback('%(<[^>]*(>|$)|>)%', array(static::class, 'split_tags'), $svg);

		// We need to decode entities in <style> tags.
		// Thanks XML!
		if (\count($style_tags)) {
			$style_tags = \array_unique($style_tags);
			foreach ($style_tags as $k=>$v) {
				$style_tags[$k] = \preg_quote($v, '@');
			}

			\preg_match_all('@<(' . \implode('|', $style_tags) . ')\b(.*?)>(.*?)</\1>@si', $svg, $matches2);
			if (\count($matches2[0])) {
				$replacements = array();
				foreach ($matches2[0] as $k=>$v) {
					// 0 Whole Match.
					// 1 Tag.
					// 2 Attributes.
					// 3 Contents.
					$replacements[$v] = "<{$matches2[1][$k]} {$matches2[2][$k]}>" .
						\strip_tags(static::sanitize_attribute_value($matches2[3][$k])) .
						"</{$matches2[1][$k]}>";
				}

				if (\count($replacements)) {
					$svg = \str_replace(\array_keys($replacements), \array_values($replacements), $svg);
				}
			}
		}

		// Remove comments.
		$svg = static::strip_comments($svg);

		// Sanitize CSS values (e.g. foo="url(...)").
		$svg = \preg_replace_callback('/url\s*\((.*)\s*\)/Ui', array(static::class, 'callback_sanitize_css_iri'), $svg);

		// Make sure if xmlns="" exists, it is correct. Can't alter
		// that with DOMDocument, and there is only one proper value.
		$svg = \preg_replace('/xmlns\s*=\s*"[^"]*"/', 'xmlns="' . static::XMLNS_NAMESPACE . '"', $svg);

		// Let's crunch some whitespace!
		$svg = \preg_replace('/\s+/u', ' ', $svg);
		$svg = \str_replace('> <', '><', $svg);

		if (
			false === ($start = mb::strpos($svg, '<svg')) ||
			false === ($end = mb::strrpos($svg, '</svg>'))
		) {
			return false;
		}

		// Add our headers, and we're done!
		if ($header) {
			$svg = static::XMLTAG . "\n" . static::get_doctype() . "\n$svg";
		}

		// Done!
		return \apply_filters('blobmimes_svg_post_sanitize', $svg);
	}

	/**
	 * Callback for static::sanitize() tag splitting
	 *
	 * @see { wp_kses_split2() }
	 *
	 * @param string $matches Matches.
	 * @return string Sanitized match replacement.
	 */
	public static function split_tags($matches) {
		$raw = \wp_kses_stripslashes($matches[0]);

		// It matched a ">" character.
		if (\substr($raw, 0, 1) !== '<') {
			return '&gt;';
		}

		// It's seriously malformed.
		if (! \preg_match('%^<\s*(/\s*)?([a-zA-Z0-9-:]+)([^>]*)>?$%', $raw, $matches)) {
			return '';
		}

		$slash = \trim($matches[1]);
		$endslash = \preg_match('/\/\s*>/', $raw) ? '/' : '';
		$tag = $matches[2];
		$attributes = $matches[3];

		// Closing tag.
		if ($slash) {
			return "</$tag>";
		}

		// Allowed values.
		$allowed_attributes = static::get_allowed_attributes();

		$parsed_attributes = static::kses_hair($attributes);
		$accepted_attributes = array();
		foreach ($parsed_attributes as $attr) {
			$attribute_name = mb::strtolower($attr['name']);

			// Bad attribute: not whitelisted.
			// data-* is implicitly whitelisted.
			if (
				! \preg_match('/^data\-/', $attribute_name) &&
				! \preg_match('/^xmlns:/', $attribute_name) &&
				! \in_array($attribute_name, $allowed_attributes, true)
			) {
				continue;
			}

			$accepted_attributes[] = $attr['whole'];
		}

		return "<$tag" . (\count($accepted_attributes) ? ' ' . \implode(' ', $accepted_attributes) : '') . "$endslash>";
	}

	/**
	 * Reworking of wp_kses_hair() for SVG
	 *
	 * @see { wp_kses_hair() }
	 *
	 * @param string $attr Attribute(s) string.
	 * @return array Attribute details.
	 */
	public static function kses_hair($attr) {
		$attributes = array();
		$mode = 0;
		$attribute = '';
		$iri_attributes = static::get_iri_attributes();

		$attr = \trim($attr);

		// Loop through the whole attribute list.
		while (\strlen($attr) !== 0) {
			$working = 0; // Was the last operation successful?

			switch ($mode) {
				case 0 : // Attribute name, href for instance.

					if (\preg_match('/^([-a-zA-Z:]+)/', $attr, $match)) {
						$attribute = $match[1];
						$working = $mode = 1;
						$attr = \preg_replace('/^[-a-zA-Z:]+/', '', $attr);
					}

					break;

				case 1 : // Equals sign or valueless ("selected").

					if (\preg_match('/^\s*=\s*/', $attr)) {
						$working = 1;
						$mode = 2;
						$attr = \preg_replace('/^\s*=\s*/', '', $attr);
						break;
					}

					if (\preg_match('/^\s+/', $attr)) {
						$working = 1;
						$mode = 0;
						if (false === \array_key_exists($attribute, $attributes)) {
							$attributes[$attribute] = array('name'=>$attribute, 'value'=>'', 'whole'=>$attribute, 'vless'=>'y');
						}
						$attr = \preg_replace('/^\s+/', '', $attr);
					}

					break;

				case 2 : // Attribute value, a URL after href= for instance.

					$attribute_value = false;

					// Double-quote.
					if (\preg_match('%^"([^"]*)"(\s+|/?$)%', $attr, $match)) {
						$attribute_value = \trim($match[1]);
						$attr = \preg_replace('/^"[^"]*"(\s+|$)/', '', $attr);
					} // Single-quote.
					elseif (\preg_match("%^'([^']*)'(\s+|/?$)%", $attr, $match)) {
						$attribute_value = \trim($match[1]);
						$attr = \preg_replace("/^'[^']*'(\s+|$)/", '', $attr);
					} // No quote.
					elseif (\preg_match("%^([^\s\"']+)(\s+|/?$)%", $attr, $match)) {
						$attribute_value = \trim($match[1]);
						$attr = \preg_replace("%^[^\s\"']+(\s+|$)%", '', $attr);
					}

					// We found a value?
					if (false !== $attribute_value) {
						$working = 1;
						$mode = 0;
						$iri = false;

						$attribute_value = static::sanitize_attribute_value($attribute_value);

						// Validate protocols.
						// IRI attributes get the full KSES treatment.
						if (
							\in_array(mb::strtolower($attribute), $iri_attributes, true) ||
							\preg_match('/^xmlns:/i', $attribute)

						) {
							$iri = true;
							$attribute_value = static::sanitize_iri_value($attribute_value);
						} // For others, we are specifically interested in removing scripty bits.
						elseif (\preg_match('/(?:\w+script):/xi', $attribute_value)) {
							$attribute_value = '';
						}

						// Save it.
						if (
							false === \array_key_exists($attribute, $attributes) &&
							(! $iri || \strlen($attribute_value))
						) {
							if (\strlen($attribute_value)) {
								$attribute_value = \esc_attr($attribute_value);
								$attributes[$attribute] = array(
									'name'=>$attribute,
									'value'=>$attribute_value,
									'whole'=>"$attribute=\"$attribute_value\"",
									'vless'=>'n',
								);
							} else {
								$attributes[$attribute] = array(
									'name'=>$attribute,
									'value'=>'',
									'whole'=>"$attribute",
									'vless'=>'y',
								);
							}
						}
					}

					break;
			} // Switch.

			if (0 === $working) {
				$attr = \wp_kses_html_error($attr);
				$mode = 0;
			}
		} // While.

		if (1 === $mode && false === \array_key_exists($attribute, $attributes)) {
			// Special case, for when the attribute list ends with a valueless
			// attribute like "selected".
			$attributes[$attribute] = array('name'=>$attribute, 'value'=>'', 'whole'=>$attribute, 'vless'=>'y');
		}

		return $attributes;
	}

	/**
	 * Import to DOMDocument object.
	 *
	 * @param string $svg SVG code or path.
	 * @return string Lightly sanitized SVG code.
	 */
	protected static function import($svg = '') {
		if (false === ($svg = static::load_svg($svg))) {
			return false;
		}

		try {
			// Run it through SimpleXML if possible.
			if (\class_exists('SimpleXMLElement')) {
				\libxml_use_internal_errors(true);
				\libxml_disable_entity_loader(true);

				$svg = new SimpleXMLElement($svg);
				$svg = $svg->asXML();
			}
		} catch (\Throwable $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}

		return $svg;
	}

	/**
	 * Get SVG Dimensions
	 *
	 * @param string $svg SVG code or path.
	 * @return array|bool Dimensions. False on failure.
	 */
	public static function get_dimensions($svg = '') {
		if (false === ($svg = static::import($svg))) {
			return false;
		}

		try {

			// Prefer SimpleXML.
			if (\class_exists('SimpleXMLElement')) {
				\libxml_use_internal_errors(true);
				\libxml_disable_entity_loader(true);

				$svg = new SimpleXMLElement($svg);

				$width = isset($svg['width']) ? (string) $svg['width'] : null;
				$height = isset($svg['height']) ? (string) $svg['height'] : null;
				$viewbox = isset($svg['viewBox']) ? (string) $svg['viewBox'] : null;
			} // Otherwise try to parse it out.
			else {
				$width = $height = $viewbox = null;

				\preg_match('/<svg\s?([^>]*)>/i', $svg, $match);
				if (\count($match) >= 2) {
					\preg_match_all('/(width|height|viewBox)\s*=\s*(([\d\.]+)|("|\')([^"\']+)("|\'))/i', $match[1], $matches);
					if (\count($matches[0])) {
						foreach ($matches[1] as $k=>$tag) {
							$tag = \strtolower($tag);
							$$tag = \strlen($matches[3][$k]) ? $matches[3][$k] : $matches[5][$k];
						}
					}
				}
			}

			// Prefer the viewbox as it is more likely to be numeric,
			// and also more likely to reflect the real dimensions.
			if (! \is_null($viewbox)) {
				$viewbox = \trim(\preg_replace('/\s+/', ' ', $viewbox));
				$viewbox = \explode(' ', $viewbox);
				if (\count($viewbox) === 4) {
					$viewbox[2] = (float) $viewbox[2];
					$viewbox[3] = (float) $viewbox[3];

					if ($viewbox[2] > 0 && $viewbox[3] > 0) {
						return array(
							'width'=>$viewbox[2],
							'height'=>$viewbox[3],
						);
					}
				}
			}

			// Otherwise maybe the width and height are good?
			if (\is_numeric($width) && \is_numeric($height)) {
				$width = (float) $width;
				$height = (float) $height;

				if ($width > 0 && $height > 0) {
					return array(
						'width'=>$width,
						'height'=>$height,
					);
				}
			}
		} catch (\Throwable $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}

		return false;
	}
}
