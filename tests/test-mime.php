<?php
/**
 * MIME tests.
 *
 * PHPUnit tests for mimes.
 *
 * @package blobfolio/mimes
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

use \blobfolio\mimes\mimes;

/**
 * Test Suite
 */
class mime_tests extends \PHPUnit\Framework\TestCase {

	const ASSETS = __DIR__ . '/assets/';

	// --------------------------------------------------------------------
	// Tests
	// --------------------------------------------------------------------

	/**
	 * ::get_mime()
	 *
	 * @dataProvider data_get_mime
	 *
	 * @param string $mime MIME.
	 * @param string $expected_mime Expected MIME.
	 * @param string $expected_ext Expected extension.
	 */
	function test_get_mime($mime, $expected_mime, $expected_ext) {
		$result = mimes::get_mime($mime);

		$this->assertSame(true, is_array($result));
		$this->assertEquals($expected_mime, $result['mime']);
		$this->assertSame(true, in_array($expected_ext, $result['ext'], true));
	}

	/**
	 * ::get_extension()
	 *
	 * @dataProvider data_get_extension
	 *
	 * @param string $ext Extension.
	 * @param string $expected_ext Expected extension.
	 * @param string $expected_mime Expected MIME.
	 */
	function test_get_extension($ext, $expected_ext, $expected_mime) {
		$result = mimes::get_extension($ext);

		$this->assertSame(true, is_array($result));
		$this->assertEquals($expected_ext, $result['ext']);
		$this->assertSame(true, in_array($expected_mime, $result['mime'], true));
	}

	/**
	 * ::finfo()
	 *
	 * @dataProvider data_finfo
	 *
	 * @param string $file File.
	 * @param mixed $expected Expected.
	 * @param mixed $suggestion Suggestion.
	 */
	function test_finfo($file, $expected, $suggestion) {
		$result = mimes::finfo($file);
		$suggested = $result['suggested_filename'];
		unset($result['suggested_filename']);

		$this->assertEquals($expected, $result);
		if ($suggestion) {
			$this->assertSame(true, in_array($suggestion, $suggested, true));
		}
	}

	// -------------------------------------------------------------------- end tests



	// --------------------------------------------------------------------
	// Data
	// --------------------------------------------------------------------

	/**
	 * Data for ::get_mime()
	 *
	 * @return array Data.
	 */
	function data_get_mime() {
		return array(
			array(
				'audio/Mp3',
				'audio/mp3',
				'mp3',
			),
			array(
				'image/jpeg',
				'image/jpeg',
				'jpeg',
			),
		);
	}

	/**
	 * Data for ::get_extension()
	 *
	 * @return array Data.
	 */
	function data_get_extension() {
		return array(
			array(
				'mp3',
				'mp3',
				'audio/mp3',
			),
			array(
				'Xls',
				'xls',
				'application/vnd.ms-office',
			),
			array(
				'.XLS',
				'xls',
				'application/vnd.ms-excel',
			),
		);
	}

	/**
	 * Data for ::finfo()
	 *
	 * @return array Data.
	 */
	function data_finfo() {
		return array(
			array(
				static::ASSETS . 'space.jpg',
				array(
					'dirname'=>rtrim(static::ASSETS, '/'),
					'basename'=>'space.jpg',
					'extension'=>'jpg',
					'filename'=>'space',
					'path'=>static::ASSETS . 'space.jpg',
					'mime'=>'image/jpeg',
				),
				null,
			),
			// SVG w/ headers.
			array(
				static::ASSETS . 'blobfolio-type.svg',
				array(
					'dirname'=>rtrim(static::ASSETS, '/'),
					'basename'=>'blobfolio-type.svg',
					'extension'=>'svg',
					'filename'=>'blobfolio-type',
					'path'=>static::ASSETS . 'blobfolio-type.svg',
					'mime'=>'image/svg+xml',
				),
				null,
			),
			// SVG w/o headers.
			array(
				static::ASSETS . 'blobfolio-no_type.svg',
				array(
					'dirname'=>rtrim(static::ASSETS, '/'),
					'basename'=>'blobfolio-no_type.svg',
					'extension'=>'svg',
					'filename'=>'blobfolio-no_type',
					'path'=>static::ASSETS . 'blobfolio-no_type.svg',
					'mime'=>'image/svg+xml',
				),
				null,
			),
			// Incorrect name.
			array(
				static::ASSETS . 'space.png',
				array(
					'dirname'=>rtrim(static::ASSETS, '/'),
					'basename'=>'space.png',
					'extension'=>'jpeg',
					'filename'=>'space',
					'path'=>static::ASSETS . 'space.png',
					'mime'=>'image/jpeg',
				),
				'space.jpg',
			),
			// Just a file name.
			array(
				'pkcs12-test-keystore.tar.gz',
				array(
					'dirname'=>getcwd(),
					'basename'=>'pkcs12-test-keystore.tar.gz',
					'extension'=>'gz',
					'filename'=>'pkcs12-test-keystore.tar',
					'path'=>getcwd() . '/pkcs12-test-keystore.tar.gz',
					'mime'=>'application/gzip',
				),
				null,
			),
			// Remote file.
			array(
				'https://upload.wikimedia.org/wikipedia/commons/7/76/Mozilla_Firefox_logo_2013.svg',
				array(
					'dirname'=>'https://upload.wikimedia.org/wikipedia/commons/7/76',
					'basename'=>'Mozilla_Firefox_logo_2013.svg',
					'extension'=>'svg',
					'filename'=>'Mozilla_Firefox_logo_2013',
					'path'=>'https://upload.wikimedia.org/wikipedia/commons/7/76/Mozilla_Firefox_logo_2013.svg',
					'mime'=>'image/svg+xml',
				),
				null,
			),
		);
	}

	// -------------------------------------------------------------------- end data
}


