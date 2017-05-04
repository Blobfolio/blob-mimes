<?php
/**
 * Class MBTests
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

use \blobfolio\wp\bm\mb;

/**
 * Test ::mime
 */
class MBTests extends WP_UnitTestCase {

	// ---------------------------------------------------------------------
	// Tests
	// ---------------------------------------------------------------------

	/**
	 * Wrapper strpos()
	 *
	 * @dataProvider data_strpos
	 *
	 * @param string $haystack Haystack.
	 * @param string $needle Needle.
	 * @param int $offset Offset.
	 * @param mixed $expected Result (mb).
	 * @param mixed $expected2 Result (non-mb).
	 */
	function test_strpos($haystack, $needle, $offset, $expected, $expected2) {
		if (!function_exists('mb_strpos')) {
			$this->assertEquals($expected2, mb::strpos($haystack, $needle, $offset));
		}
		else {
			$this->assertEquals($expected, mb::strpos($haystack, $needle, $offset));
		}

		$this->assertEquals($expected2, strpos($haystack, $needle, $offset));
	}

	/**
	 * Wrapper strrpos()
	 *
	 * @dataProvider data_strrpos
	 *
	 * @param string $haystack Haystack.
	 * @param string $needle Needle.
	 * @param int $offset Offset.
	 * @param mixed $expected Result (mb).
	 * @param mixed $expected2 Result (non-mb).
	 */
	function test_strrpos($haystack, $needle, $offset, $expected, $expected2) {
		if (!function_exists('mb_strrpos')) {
			$this->assertEquals($expected2, mb::strrpos($haystack, $needle, $offset));
		}
		else {
			$this->assertEquals($expected, mb::strrpos($haystack, $needle, $offset));
		}

		$this->assertEquals($expected2, strrpos($haystack, $needle, $offset));
	}

	/**
	 * Wrapper strtolower()
	 *
	 * @dataProvider data_strtolower
	 *
	 * @param string $str String.
	 * @param mixed $expected Result (mb).
	 * @param mixed $expected2 Result (non-mb).
	 */
	function test_strtolower($str, $expected, $expected2) {
		if (!function_exists('mb_strtolower')) {
			$this->assertEquals($expected2, mb::strtolower($str));
		}
		else {
			$this->assertEquals($expected, mb::strtolower($str));
		}

		$this->assertEquals($expected2, strtolower($str));
	}

	/**
	 * Wrapper substr()
	 *
	 * @dataProvider data_substr
	 *
	 * @param string $str String.
	 * @param int $start Start.
	 * @param int $length Length.
	 * @param mixed $expected Result (mb).
	 * @param mixed $expected2 Result (non-mb).
	 */
	function test_substr($str, $start, $length, $expected, $expected2) {
		if (!function_exists('mb_substr')) {
			$this->assertEquals($expected2, mb::substr($str, $start, $length));
		}
		else {
			$this->assertEquals($expected, mb::substr($str, $start, $length));
		}

		$this->assertEquals($expected2, substr($str, $start, $length));
	}

	// --------------------------------------------------------------------- end tests



	// ---------------------------------------------------------------------
	// Test Data
	// ---------------------------------------------------------------------

	/**
	 * Data: strpos()
	 *
	 * @return array Tests.
	 */
	function data_strpos() {
		return array(
			array(
				'AöA',
				'E',
				0,
				false,
				false
			),
			array(
				'AöA',
				'A',
				0,
				0,
				0
			),
			array(
				'AöA',
				'ö',
				0,
				1,
				1
			),
			array(
				'AöA',
				'A',
				1,
				2,
				3
			),
		);
	}

	/**
	 * Data: strpos()
	 *
	 * @return array Tests.
	 */
	function data_strrpos() {
		return array(
			array(
				'Björk',
				'E',
				0,
				false,
				false
			),
			array(
				'Björk',
				'k',
				0,
				4,
				5
			),
			array(
				'Björk',
				'ö',
				0,
				2,
				2
			),
			array(
				'Björk',
				'j',
				1,
				1,
				1
			),
		);
	}


	/**
	 * Data: strpos()
	 *
	 * @return array Tests.
	 */
	function data_strtolower() {
		return array(
			array(
				'BJÖRK',
				'björk',
				'bjÖrk'
			)
		);
	}


	/**
	 * Data: strpos()
	 *
	 * @return array Tests.
	 */
	function data_substr() {
		return array(
			array(
				'Björk',
				0,
				4,
				'Björ',
				'Bjö'
			),
			array(
				'Björk',
				4,
				null,
				'k',
				''
			),
		);
	}

	// --------------------------------------------------------------------- end test data

}
