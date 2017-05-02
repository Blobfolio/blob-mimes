<?php
/**
 * Class MimeTests
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

use \blobfolio\wp\bm\mime;
use \blobfolio\wp\bm\mime\aliases;

/**
 * Test ::mime
 */
class MimeTests extends WP_UnitTestCase {

	// ---------------------------------------------------------------------
	// Tests
	// ---------------------------------------------------------------------

	/**
	 * Get MIME Aliases
	 *
	 * @dataProvider data_get_aliases
	 *
	 * @param string $ext Extension.
	 * @param mixed $expected Result.
	 */
	function test_get_aliases($ext, $expected) {
		$this->assertEquals($expected, mime::get_aliases($ext));
	}

	/**
	 * Get MIME Aliases: Filtered
	 */
	function test_filter_blobmimes_get_mime_aliases() {
		$this->assertEquals(false, mime::get_aliases('isnotreal'));

		add_filter('blobmimes_get_mime_aliases', array($this, 'callback_filter_blobmimes_get_mime_aliases'), 10, 2);

		$this->assertEquals(array('awesome/sauce'), mime::get_aliases('isnotreal'));

		remove_filter('blobmimes_get_mime_aliases', array($this, 'callback_filter_blobmimes_get_mime_aliases'), 10);
	}

	/**
	 * Rename File
	 *
	 * @dataProvider data_update_filename_extension
	 *
	 * @param string $filename File name.
	 * @param string $ext Extension.
	 * @param string $expected Result.
	 */
	function test_update_filename_extension($filename, $ext, $expected) {
		$this->assertEquals($expected, mime::update_filename_extension($filename, $ext));
	}

	/**
	 * Check Alias
	 *
	 * @dataProvider data_check_alias
	 *
	 * @param string $ext Extension.
	 * @param string $mime MIME.
	 * @param bool $expected Result.
	 */
	function test_check_alias($ext, $mime, $expected) {
		$this->assertEquals($expected, mime::check_alias($ext, $mime));
	}

	/**
	 * Check Alias: Filtered
	 */
	function test_filter_blobmimes_check_mime_alias() {
		$this->assertEquals(false, mime::check_alias('isnotreal'));

		add_filter('blobmimes_check_mime_alias', array($this, 'callback_filter_blobmimes_check_mime_alias'), 10, 3);

		$this->assertEquals(true, mime::check_alias('isnotreal'));

		remove_filter('blobmimes_check_mime_alias', array($this, 'callback_filter_blobmimes_check_mime_alias'), 10);
	}

	/**
	 * Check Real Filetype
	 *
	 * @dataProvider data_check_real_filetype
	 *
	 * @param string $file File.
	 * @param string $filename File name.
	 * @param mixed $mimes Allowed MIMEs.
	 * @param bool $expected Result.
	 */
	function test_check_real_filetype($file, $filename, $mimes, $expected) {
		if (
			!function_exists('finfo_file') ||
			!defined('FILEINFO_MIME_TYPE')
		) {
			$this->markTestSkipped('fileinfo.so not installed.');
		}

		$this->assertEquals($expected, mime::check_real_filetype($file, $filename, $mimes));
	}

	/**
	 * Check Real Filetype (XLS)
	 *
	 * So many damn ways to be an XLS file. Haha.
	 *
	 * @dataProvider data_check_real_filetype_xls
	 *
	 * @param string $file File.
	 * @param string $filename File name.
	 * @param bool $expected Result.
	 */
	function test_check_real_filetype_xls($file, $filename, $expected) {
		if (
			!function_exists('finfo_file') ||
			!defined('FILEINFO_MIME_TYPE')
		) {
			$this->markTestSkipped('fileinfo.so not installed.');
		}

		$result = mime::check_real_filetype($file, $filename);
		$this->assertEquals($expected, mime::check_alias('xls', $result['type']));
	}

	/**
	 * Check Alias: Filtered
	 */
	function test_filter_blobmimes_check_real_filetype() {
		if (
			!function_exists('finfo_file') ||
			!defined('FILEINFO_MIME_TYPE')
		) {
			$this->markTestSkipped('fileinfo.so not installed.');
		}

		$this->assertEquals(array('ext'=>false, 'type'=>false), mime::check_real_filetype('isnotreal', 'isnotreal'));

		add_filter('blobmimes_check_real_filetype', array($this, 'callback_filter_blobmimes_check_real_filetype'), 10, 4);

		$this->assertEquals(array('ext'=>'is', 'type'=>'real'), mime::check_real_filetype('isnotreal'));

		remove_filter('blobmimes_check_real_filetype', array($this, 'callback_filter_blobmimes_check_real_filetype'), 10);
	}

	// --------------------------------------------------------------------- end tests



	// ---------------------------------------------------------------------
	// Test Data
	// ---------------------------------------------------------------------

	/**
	 * Data: Get MIME Aliases
	 *
	 * @return array Tests.
	 */
	function data_get_aliases() {
		return array(
			array(
				'jpg',
				aliases::$data['jpg']
			),
			array(
				'.jpg',
				aliases::$data['jpg']
			),
			array(
				'JPG',
				aliases::$data['jpg']
			),
			array(
				'isnotreal',
				false
			),
		);
	}

	/**
	 * Data: Rename File
	 *
	 * @return array Tests.
	 */
	function data_update_filename_extension() {
		return array(
			array(
				'apples.jpg',
				'jpg',
				'apples.jpg'
			),
			array(
				'apples',
				'jpg',
				'apples.jpg'
			),
			array(
				'apples.gif',
				'jpg',
				'apples.jpg'
			),
			array(
				'.apples',
				'jpg',
				'.jpg'
			),
		);
	}

	/**
	 * Data: Check Alias
	 *
	 * @return array Tests.
	 */
	function data_check_alias() {
		return array(
			array(
				'jpg',
				'image/jpeg',
				true
			),
			array(
				'jpg',
				'image/x-jpeg',
				true
			),
			array(
				'jpg',
				'image/vnd.jpeg',
				true
			),
			array(
				'jpg',
				'image/gif',
				false
			),
			array(
				'xls',
				'application/CDFV2-encrypted',
				true
			),
			array(
				'xls',
				'application/Octet-Stream',
				true
			),
			array(
				'isnotreal',
				'awesome/sauce',
				false
			),
		);
	}

	/**
	 * Data: Check Real Filetype
	 *
	 * @return array Tests.
	 */
	function data_check_real_filetype() {
		$dir = dirname(__FILE__) . '/assets/';

		return array(
			array(
				$dir . 'space.jpg',
				'space.jpg',
				null,
				array(
					'ext'=>'jpg',
					'type'=>'image/jpeg'
				)
			),
			array(
				$dir . 'space.png',
				'space.jpg',
				null,
				array(
					'ext'=>'jpg',
					'type'=>'image/jpeg'
				)
			),
		);
	}

	/**
	 * Data: Check Real Filetype XLS
	 *
	 * @return array Tests.
	 */
	function data_check_real_filetype_xls() {
		$dir = dirname(__FILE__) . '/assets/';

		return array(
			array(
				$dir . 'xls-cdfv2.xls',
				'test.xls',
				true
			),
			array(
				$dir . 'xls-excel.xls',
				'test.xls',
				true
			),
			array(
				$dir . 'xls-msoffice.xls',
				'test.xls',
				true
			),
			array(
				$dir . 'xls-xml.xls',
				'test.xls',
				true
			),
		);
	}

	// --------------------------------------------------------------------- end test data



	// ---------------------------------------------------------------------
	// Callbacks
	// ---------------------------------------------------------------------

	/**
	 * Callback: Get MIME Aliases
	 *
	 * @param mixed $match Match.
	 * @param string $ext Extension.
	 * @return mixed Match or false.
	 */
	function callback_filter_blobmimes_get_mime_aliases($match, $ext) {
		if ('isnotreal' === $ext) {
			$match = array('awesome/sauce');
		}

		return $match;
	}

	/**
	 * Callback: Check MIME Alias
	 *
	 * @param bool $match Match.
	 * @param string $ext Extension.
	 * @param string $mime MIME.
	 * @return bool True/false.
	 */
	function callback_filter_blobmimes_check_mime_alias($match, $ext, $mime) {
		if ('isnotreal' === $ext) {
			$match = true;
		}

		return $match;
	}

	/**
	 * Callback: Check Real Filetype
	 *
	 * @param bool $match Match.
	 * @param string $file File path.
	 * @param string $filename File name.
	 * @param array $mimes Allowed mimes.
	 * @return bool True/false.
	 */
	function callback_filter_blobmimes_check_real_filetype($match, $file, $filename, $mimes) {
		if ('isnotreal' === $file) {
			$match = array(
				'ext'=>'is',
				'type'=>'real'
			);
		}

		return $match;
	}

	// --------------------------------------------------------------------- end test data

}
