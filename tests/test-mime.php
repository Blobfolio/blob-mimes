<?php
/**
 * MIME tests.
 *
 * PHPUnit tests for \blobfolio\mimes\mimes.
 *
 * @package blobfolio/mimes
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Test Suite
 */
class mime_tests extends \PHPUnit\Framework\TestCase {

	const ASSETS = __DIR__ . '/assets/';

	/**
	 * ::get_mime()
	 *
	 * @return void Nothing.
	 */
	function test_get_mime() {
		$thing = \blobfolio\mimes\mimes::get_mime('audio/mp3');
		$this->assertEquals(true, is_array($thing));
		$this->assertEquals('audio/mp3', $thing['mime']);
		$this->assertEquals(true, in_array('mp3', $thing['ext'], true));
	}

	/**
	 * ::get_extension()
	 *
	 * @return void Nothing.
	 */
	function test_get_extension() {
		$thing = \blobfolio\mimes\mimes::get_extension('xls');
		$this->assertEquals(true, is_array($thing));
		$this->assertEquals('xls', $thing['ext']);
		$this->assertEquals(true, in_array('application/vnd.ms-excel', $thing['mime'], true));
	}

	/**
	 * ::finfo()
	 *
	 * Test an actual file.
	 *
	 * @return void Nothing.
	 */
	function test_finfo_correct() {
		$thing = \blobfolio\mimes\mimes::finfo(self::ASSETS . 'space.jpg');

		$this->assertEquals(true, is_array($thing));
		$this->assertEquals('space.jpg', $thing['basename']);
		$this->assertEquals('jpg', $thing['extension']);
		$this->assertEquals('space', $thing['filename']);
		$this->assertEquals('image/jpeg', $thing['mime']);
		$this->assertEquals(0, count($thing['suggested_filename']));
	}

	/**
	 * ::finfo()
	 *
	 * Test an actual file, incorrectly named.
	 *
	 * @return void Nothing.
	 */
	function test_finfo_incorrect() {
		$thing = \blobfolio\mimes\mimes::finfo(self::ASSETS . 'space.png');

		$this->assertEquals(true, is_array($thing));
		$this->assertEquals('space.png', $thing['basename']);
		$this->assertEquals('jpeg', $thing['extension']);
		$this->assertEquals('space', $thing['filename']);
		$this->assertEquals('image/jpeg', $thing['mime']);
		$this->assertEquals(true, in_array('space.jpg', $thing['suggested_filename'], true));
	}

	/**
	 * ::finfo()
	 *
	 * Test a file name.
	 *
	 * @return void Nothing.
	 */
	function test_finfo_just_filename() {
		$thing = \blobfolio\mimes\mimes::finfo('pkcs12-test-keystore.tar.gz');

		$this->assertEquals(true, is_array($thing));
		$this->assertEquals('pkcs12-test-keystore.tar.gz', $thing['basename']);
		$this->assertEquals('gz', $thing['extension']);
		$this->assertEquals('pkcs12-test-keystore.tar', $thing['filename']);
		$this->assertEquals('application/gzip', $thing['mime']);
	}

	/**
	 * ::finfo()
	 *
	 * Test a remote file.
	 *
	 * @return void Nothing.
	 */
	function test_finfo_remote() {
		$thing = \blobfolio\mimes\mimes::finfo('https://upload.wikimedia.org/wikipedia/commons/7/76/Mozilla_Firefox_logo_2013.svg');

		$this->assertEquals(true, is_array($thing));
		$this->assertEquals('Mozilla_Firefox_logo_2013.svg', $thing['basename']);
		$this->assertEquals('svg', $thing['extension']);
		$this->assertEquals('Mozilla_Firefox_logo_2013', $thing['filename']);
		$this->assertEquals('image/svg+xml', $thing['mime']);
	}

	/**
	 * ::check_ext_and_mime()
	 *
	 * Test various XLS files.
	 *
	 * @return void Nothing.
	 */
	function test_check_ext_and_mime() {
		if (
			!function_exists('finfo_file') ||
			!defined('FILEINFO_MIME_TYPE')
		){
			$this->markTestSkipped('fileinfo.so not installed.');
		}

		$things = array(
			'xls-cdfv2.xls',
			'xls-excel.xls',
			'xls-msoffice.xls',
			'xls-xml.xls'
		);

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		foreach ($things as $thing) {
			$mime = finfo_file($finfo, self::ASSETS . $thing);
			$this->assertEquals(true, \blobfolio\mimes\mimes::check_ext_and_mime('xls', $mime));
		}
		finfo_close($finfo);
	}
}


