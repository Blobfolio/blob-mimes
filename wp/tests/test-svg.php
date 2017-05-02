<?php
/**
 * Class SVGTests
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

use \blobfolio\wp\bm\svg\svg_dom;
use \blobfolio\wp\bm\svg\svg_fallback;

/**
 * Test ::mime
 */
class SVGTests extends WP_UnitTestCase {

	// ---------------------------------------------------------------------
	// Tests
	// ---------------------------------------------------------------------

	/**
	 * Sanitize: DOMDocument
	 *
	 * @dataProvider data_sanitize
	 *
	 * @param string $svg SVG content.
	 */
	function test_sanitize_dom($svg) {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$tests = array(
			'&#109;',
			'&#123',
			'//hello',
			'<script',
			'comment',
			'data:',
			'Gotcha',
			'http://example.com',
			'max:volume',
			'onclick',
			'onload',
			'xmlns:foobar',
			'XSS',
		);

		$result = svg_dom::sanitize($svg);

		foreach ($tests as $v) {
			$this->assertEquals(false, strpos($result, $v));
		}
	}

	/**
	 * Sanitize: Fallback
	 *
	 * @dataProvider data_sanitize
	 *
	 * @param string $svg SVG content.
	 */
	function test_sanitize_fallback($svg) {
		$tests = array(
			'&#109;',
			'&#123',
			'//hello',
			'<script',
			'comment',
			'data:',
			'Gotcha',
			'http://example.com',
			'max:volume',
			'onclick',
			'onload',
			'xmlns:foobar',
			'XSS',
		);

		$result = svg_fallback::sanitize($svg);

		foreach ($tests as $v) {
			$this->assertEquals(false, strpos($result, $v));
		}
	}

	/**
	 * Sanitize: DOMDocument Dimensions
	 *
	 * @dataProvider data_dimensions
	 *
	 * @param string $svg SVG content.
	 * @param mixed $expected Excepted.
	 */
	function test_dimensions_dom($svg, $expected) {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$this->assertEquals($expected, svg_dom::get_dimensions($svg));
	}

	/**
	 * Sanitize: Fallback Dimensions
	 *
	 * @dataProvider data_dimensions
	 *
	 * @param string $svg SVG content.
	 * @param mixed $expected Excepted.
	 */
	function test_dimensions_fallback($svg, $expected) {
		$this->assertEquals($expected, svg_fallback::get_dimensions($svg));
	}

	/**
	 * Sanitize: DOMDocument Allowed Domains
	 */
	function test_filter_blobmimes_svg_allowed_domains_dom() {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$svg = file_get_contents(dirname(__FILE__) . '/assets/monogram-inkscape.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, 'purl.org'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_dom::sanitize($svg), 'purl.org'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_domains', array($this, 'callback_filter_blobmimes_svg_allowed_domains'));
		$this->assertEquals(true, !!strpos(svg_dom::sanitize($svg), 'purl.org'));
		remove_filter('blobmimes_svg_allowed_domains', array($this, 'callback_filter_blobmimes_svg_allowed_domains'));
	}

	/**
	 * Sanitize: Fallback Allowed Domains
	 */
	function test_filter_blobmimes_svg_allowed_domains_fallback() {
		$svg = file_get_contents(dirname(__FILE__) . '/assets/monogram-inkscape.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, 'purl.org'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_fallback::sanitize($svg), 'purl.org'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_domains', array($this, 'callback_filter_blobmimes_svg_allowed_domains'));
		$this->assertEquals(true, !!strpos(svg_fallback::sanitize($svg), 'purl.org'));
		remove_filter('blobmimes_svg_allowed_domains', array($this, 'callback_filter_blobmimes_svg_allowed_domains'));
	}

	/**
	 * Sanitize: DOMDocument Allowed Protocols
	 */
	function test_filter_blobmimes_svg_allowed_protocols_dom() {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, 'javascript:'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_dom::sanitize($svg), 'javascript:'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_protocols', array($this, 'callback_filter_blobmimes_svg_allowed_protocols'));
		$this->assertEquals(true, !!strpos(svg_dom::sanitize($svg), 'javascript:'));
		remove_filter('blobmimes_svg_allowed_protocols', array($this, 'callback_filter_blobmimes_svg_allowed_protocols'));
	}

	/**
	 * Sanitize: Fallback Allowed Protocols
	 */
	function test_filter_blobmimes_svg_allowed_protocols_fallback() {
		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, 'javascript:'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_fallback::sanitize($svg), 'javascript:'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_protocols', array($this, 'callback_filter_blobmimes_svg_allowed_protocols'));
		$this->assertEquals(true, !!strpos(svg_fallback::sanitize($svg), 'javascript:'));
		remove_filter('blobmimes_svg_allowed_protocols', array($this, 'callback_filter_blobmimes_svg_allowed_protocols'));
	}

	/**
	 * Sanitize: DOMDocument Allowed Tags
	 */
	function test_filter_blobmimes_svg_allowed_tags_dom() {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, '<script>'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_dom::sanitize($svg), 'java<script>cript:'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_tags', array($this, 'callback_filter_blobmimes_svg_allowed_tags'));
		$this->assertEquals(true, !!strpos(svg_dom::sanitize($svg), '<script>'));
		remove_filter('blobmimes_svg_allowed_tags', array($this, 'callback_filter_blobmimes_svg_allowed_tags'));
	}

	/**
	 * Sanitize: Fallback Allowed Tags
	 */
	function test_filter_blobmimes_svg_allowed_tags_fallback() {
		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, '<script>'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_fallback::sanitize($svg), '<script>'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_tags', array($this, 'callback_filter_blobmimes_svg_allowed_tags'));
		$this->assertEquals(true, !!strpos(svg_fallback::sanitize($svg), '<script>'));
		remove_filter('blobmimes_svg_allowed_tags', array($this, 'callback_filter_blobmimes_svg_allowed_tags'));
	}

	/**
	 * Sanitize: DOMDocument Allowed Attributes
	 */
	function test_filter_blobmimes_svg_allowed_attributes_dom() {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, 'onload'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_dom::sanitize($svg), 'onload'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_attributes', array($this, 'callback_filter_blobmimes_svg_allowed_attributes'));
		$this->assertEquals(true, !!strpos(svg_dom::sanitize($svg), 'onload'));
		remove_filter('blobmimes_svg_allowed_attributes', array($this, 'callback_filter_blobmimes_svg_allowed_attributes'));
	}

	/**
	 * Sanitize: Fallback Allowed Attributes
	 */
	function test_filter_blobmimes_svg_allowed_attributes_fallback() {
		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Original.
		$this->assertEquals(true, !!strpos($svg, 'onload'));

		// Default sanitize.
		$this->assertEquals(false, !!strpos(svg_fallback::sanitize($svg), 'onload'));

		// Filtered sanitize.
		add_filter('blobmimes_svg_allowed_attributes', array($this, 'callback_filter_blobmimes_svg_allowed_attributes'));
		$this->assertEquals(true, !!strpos(svg_fallback::sanitize($svg), 'onload'));
		remove_filter('blobmimes_svg_allowed_attributes', array($this, 'callback_filter_blobmimes_svg_allowed_attributes'));
	}

	/**
	 * Sanitize: DOMDocument Pre-Sanitize
	 */
	function test_filter_blobmimes_svg_pre_sanitize_dom() {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Filtered sanitize.
		add_filter('blobmimes_svg_pre_sanitize', array($this, 'callback_filter_blobmimes_svg_pre_sanitize'));
		$this->assertNotEquals(false, strpos(svg_dom::sanitize($svg), 'presanitized'));
		remove_filter('blobmimes_svg_pre_sanitize', array($this, 'callback_filter_blobmimes_svg_pre_sanitize'));
	}

	/**
	 * Sanitize: Fallback Pre-Sanitize
	 */
	function test_filter_blobmimes_svg_pre_sanitize_fallback() {
		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Filtered sanitize.
		add_filter('blobmimes_svg_pre_sanitize', array($this, 'callback_filter_blobmimes_svg_pre_sanitize'));
		$this->assertNotEquals(false, strpos(svg_fallback::sanitize($svg), 'presanitized'));
		remove_filter('blobmimes_svg_pre_sanitize', array($this, 'callback_filter_blobmimes_svg_pre_sanitize'));
	}

	/**
	 * Sanitize: DOMDocument Post-Sanitize
	 */
	function test_filter_blobmimes_svg_post_sanitize_dom() {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Filtered sanitize.
		add_filter('blobmimes_svg_post_sanitize', array($this, 'callback_filter_blobmimes_svg_post_sanitize'));
		$this->assertEquals('postsanitized', svg_dom::sanitize($svg));
		remove_filter('blobmimes_svg_post_sanitize', array($this, 'callback_filter_blobmimes_svg_post_sanitize'));
	}

	/**
	 * Sanitize: Fallback Post-Sanitize
	 */
	function test_filter_blobmimes_svg_post_sanitize_fallback() {
		$svg = file_get_contents(dirname(__FILE__) . '/assets/enshrined.svg');

		// Filtered sanitize.
		add_filter('blobmimes_svg_post_sanitize', array($this, 'callback_filter_blobmimes_svg_post_sanitize'));
		$this->assertEquals('postsanitized', svg_fallback::sanitize($svg));
		remove_filter('blobmimes_svg_post_sanitize', array($this, 'callback_filter_blobmimes_svg_post_sanitize'));
	}

	/**
	 * Sanitize: DOMDocument Doctype
	 */
	function test_filter_blobmimes_svg_doctype_dom() {
		if (!class_exists('DOMDocument') || !class_exists('DOMXPath')) {
			$this->markTestSkipped('DOM is not installed.');
		}

		$svg = dirname(__FILE__) . '/assets/enshrined.svg';

		// Filtered sanitize.
		add_filter('blobmimes_svg_doctype', array($this, 'callback_filter_blobmimes_svg_doctype'));
		$this->assertNotEquals(false, strpos(svg_dom::sanitize($svg), 'xhtml-math-svg'));
		$this->assertEquals(false, strpos(svg_dom::sanitize($svg, false), 'xhtml-math-svg'));
		remove_filter('blobmimes_svg_doctype', array($this, 'callback_filter_blobmimes_svg_doctype'));
	}

	/**
	 * Sanitize: Fallback Doctype
	 */
	function test_filter_blobmimes_svg_doctype_fallback() {
		$svg = dirname(__FILE__) . '/assets/enshrined.svg';

		// Filtered sanitize.
		add_filter('blobmimes_svg_doctype', array($this, 'callback_filter_blobmimes_svg_doctype'));
		$this->assertNotEquals(false, strpos(svg_fallback::sanitize($svg), 'xhtml-math-svg'));
		$this->assertEquals(false, strpos(svg_fallback::sanitize($svg, false), 'xhtml-math-svg'));
		remove_filter('blobmimes_svg_doctype', array($this, 'callback_filter_blobmimes_svg_doctype'));
	}

	// --------------------------------------------------------------------- end tests



	// ---------------------------------------------------------------------
	// Test Data
	// ---------------------------------------------------------------------

	/**
	 * Data: Sanitize
	 *
	 * @return array Tests.
	 */
	function data_sanitize() {
		$dir = dirname(__FILE__) . '/assets/';

		return array(
			array($dir . 'monogram.svg'),
			array(file_get_contents($dir . 'monogram.svg')),
			array($dir . 'enshrined.svg'),
			array($dir . 'monogram-inkscape.svg'),
			array($dir . 'pi.svg'),
			array($dir . 'minus.svg')
		);
	}

	/**
	 * Data: Dimensions
	 *
	 * @return array Tests.
	 */
	function data_dimensions() {
		$dir = dirname(__FILE__) . '/assets/';

		return array(
			array(
				$dir . 'monogram.svg',
				array('width'=>330.056, 'height'=>495.558)
			),
			array(
				file_get_contents($dir . 'monogram.svg'),
				array('width'=>330.056, 'height'=>495.558)
			),
		);
	}

	// --------------------------------------------------------------------- end test data



	// ---------------------------------------------------------------------
	// Callbacks
	// ---------------------------------------------------------------------

	/**
	 * Callback: Filter allowed domains
	 *
	 * @param array $domains Domains.
	 * @return array Domains.
	 */
	function callback_filter_blobmimes_svg_allowed_domains($domains) {
		$domains[] = 'purl.org';
		return $domains;
	}

	/**
	 * Callback: Filter allowed protocols
	 *
	 * @param array $protocols Protocols.
	 * @return array Protocols.
	 */
	function callback_filter_blobmimes_svg_allowed_protocols($protocols) {
		$protocols[] = 'javascript';
		return $protocols;
	}

	/**
	 * Callback: Filter allowed attributes
	 *
	 * @param array $attributes Attributes.
	 * @return array Attributes.
	 */
	function callback_filter_blobmimes_svg_allowed_attributes($attributes) {
		$attributes[] = 'onload';
		return $attributes;
	}

	/**
	 * Callback: Filter allowed tags
	 *
	 * @param array $tags Tags.
	 * @return array Tags.
	 */
	function callback_filter_blobmimes_svg_allowed_tags($tags) {
		$tags[] = 'script';
		return $tags;
	}

	/**
	 * Callback: Filter Pre-Sanitize
	 *
	 * @param string $svg SVG.
	 * @return string SVG.
	 */
	function callback_filter_blobmimes_svg_pre_sanitize($svg) {
		$svg = str_replace('<svg ', '<svg id="presanitized" ', $svg);
		return $svg;
	}

	/**
	 * Callback: Filter Post-Sanitize
	 *
	 * @param string $svg SVG.
	 * @return string SVG.
	 */
	function callback_filter_blobmimes_svg_post_sanitize($svg) {
		$svg = 'postsanitized';
		return $svg;
	}

	/**
	 * Callback: Filter Doctype
	 *
	 * @param string $doctype Doctype.
	 * @return string Doctype.
	 */
	function callback_filter_blobmimes_svg_doctype($doctype) {
		$doctype = '<!DOCTYPE svg:svg PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN" "http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd">';
		return $doctype;
	}

	// --------------------------------------------------------------------- end test data

}
