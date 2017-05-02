<?php
// @codingStandardsIgnoreFile
/**
 * Lord of the Files - SVG Wrapper
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

// DomDocument is better, but not everyone can do it.
if (class_exists('DOMDocument') && class_exists('DOMXPath')) {
	class svg extends svg\svg_dom {

	}
}
// Otherwise there's a decent fallback.
else {
	class svg extends svg\svg_fallback {

	}
}
