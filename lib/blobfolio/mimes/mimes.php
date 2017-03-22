<?php
/**
 * MIME, Extension, File-handling
 *
 * This class contains functions for locating and
 * parsing MIME type and file extension data.
 *
 * @package blobfolio/mimes
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\mimes;

class mimes {

	// Our data.
	protected static $by_mime;
	protected static $by_ext;

	const BY_MIME_FILE = 'extensions_by_mime.json';
	const BY_EXT_FILE = 'mimes_by_extension.json';
	const MIME_DEFAULT = 'application/octet-stream';



	// ---------------------------------------------------------------------
	// Data Population
	// ---------------------------------------------------------------------

	/**
	 * Load JSON
	 *
	 * A wrapper function for parsing the MIME/ext
	 * data being stored in .JSON files.
	 *
	 * @param string $path JSON file path.
	 * @return array Decoded data.
	 * @throws \Exception Invalid file path.
	 * @throws \Exception Invalid file contents.
	 */
	protected static function load_json(string $path = '') {
		$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . "$path";
		\blobfolio\common\ref\file::path($path, true);

		if (false === $path) {
			throw new \Exception('Invalid JSON path.');
		}

		$data = \blobfolio\common\cast::string(@file_get_contents($path));
		$data = json_decode($data, true);

		if (!is_array($data)) {
			throw new \Exception('Invalid JSON data.');
		}

		return $data;
	}

	/**
	 * Load MIME data.
	 *
	 * @return bool True.
	 * @throws \Exception Missing database.
	 */
	protected static function load_mimes() {
		// Populate MIME data if needed.
		if (!is_array(static::$by_mime)) {
			if (false === static::$by_mime = static::load_json(static::BY_MIME_FILE)) {
				throw new \Exception('Could not load MIME database.');
			}
		}

		return true;
	}

	/**
	 * Load Exension Data.
	 *
	 * @return bool True.
	 * @throws \Exception Missing database.
	 */
	protected static function load_extensions() {
		if (!is_array(static::$by_ext)) {
			if (false === static::$by_ext = static::load_json(static::BY_EXT_FILE)) {
				throw new \Exception('Could not load file extension database.');
			}
		}

		return true;
	}

	// --------------------------------------------------------------------- end data population



	// ---------------------------------------------------------------------
	// Public Data Access
	// ---------------------------------------------------------------------

	/**
	 * Get All MIMEs
	 *
	 * Return the entire parsed MIME database.
	 *
	 * @return array MIME data.
	 */
	public static function get_mimes() {
		static::load_mimes();
		return static::$by_mime;
	}

	/**
	 * Get One MIME
	 *
	 * Return information about a single MIME type.
	 *
	 * @param string $mime MIME type.
	 * @return array MIME data.
	 */
	public static function get_mime(string $mime = '') {
		\blobfolio\common\ref\sanitize::mime($mime);
		static::load_mimes();
		return isset(static::$by_mime[$mime]) ? static::$by_mime[$mime] : false;
	}

	/**
	 * Get All Extensions
	 *
	 * Return the entire file extension database.
	 *
	 * @return array Extension data.
	 */
	public static function get_extensions() {
		static::load_extensions();
		return static::$by_ext;
	}

	/**
	 * Get One Extension
	 *
	 * Return information about a single file extension.
	 *
	 * @param string $ext File extension.
	 * @return array Extension data.
	 */
	public static function get_extension(string $ext = '') {
		\blobfolio\common\ref\sanitize::file_extension($ext);
		static::load_extensions();
		return isset(static::$by_ext[$ext]) ? static::$by_ext[$ext] : false;
	}

	/**
	 * Verify a MIME/ext pairing
	 *
	 * @param string $ext File extension.
	 * @param string $mime MIME type.
	 * @param bool $soft Soft pass not-found.
	 * @return bool True.
	 */
	public static function check_ext_and_mime(string $ext = '', string $mime = '', bool $soft=true) {
		\blobfolio\common\ref\sanitize::file_extension($ext);
		if (!\blobfolio\common\mb::strlen($ext)) {
			return false;
		}

		\blobfolio\common\ref\sanitize::mime($mime);
		if (!strlen($mime) || ($soft && static::MIME_DEFAULT === $mime)) {
			return true;
		}

		// Soft pass on extension fail.
		if (false === $ext = static::get_extension($ext)) {
			return $soft;
		}

		// Before looking for matches, convert any generic CDFV2
		// types into an equally generic, but less variable type.
		if (0 === strpos($mime, 'application/cdfv2')) {
			$mime = 'application/vnd.ms-office';
		}

		// Loose mime check.
		$real = $ext['mime'];
		$test = array($mime);

		$parts = explode('/', $mime);
		$subtype = count($parts) - 1;
		if ('x-' === substr($parts[$subtype], 0, 2)) {
			$parts[$subtype] = substr($parts[$subtype], 2);
		} else {
			$parts[$subtype] = 'x-' . $parts[$subtype];
		}
		$test[] = implode('/', $parts);

		$parts = explode('/', $mime);
		$subtype = count($parts) - 1;
		if ('vnd.' === substr($parts[$subtype], 0, 4)) {
			$parts[$subtype] = substr($parts[$subtype], 4);
		} else {
			$parts[$subtype] = 'vnd.' . $parts[$subtype];
		}
		$test[] = implode('/', $parts);

		// Any overlap?
		$found = array_intersect($real, $test);
		return count($found) > 0;
	}

	/**
	 * Get File Info
	 *
	 * This function is a sexier version of finfo_open().
	 *
	 * @param string $path File path or name.
	 * @param string $nice Nice file name (for e.g. tmp uploads).
	 * @return array File data.
	 */
	public static function finfo(string $path = '', string $nice = null) {
		$out = array(
			'dirname'=>'',
			'basename'=>'',
			'extension'=>'',
			'filename'=>'',
			'path'=>'',
			'mime'=>static::MIME_DEFAULT,
			'suggested_filename'=>array(),
		);

		// Path might just be an extension.
		\blobfolio\common\ref\cast::string($path);
		if (false === \blobfolio\common\mb::strpos($path, '.') &&
			false === \blobfolio\common\mb::strpos($path, '/') &&
			false === \blobfolio\common\mb::strpos($path, '\\')
		) {
			$out['extension'] = \blobfolio\common\sanitize::file_extension($path);
			if (false !== ($ext = static::get_extension($path))) {
				$out['mime'] = $ext['primary'];
			}

			return $out;
		}

		// Path is something path-like.
		\blobfolio\common\ref\file::path($path, false);
		$out['path'] = $path;
		$out = \blobfolio\common\data::parse_args(pathinfo($path), $out);

		if (is_string($nice)) {
			$pathinfo = pathinfo($nice);
			$out['filename'] = isset($pathinfo['filename']) ? $pathinfo['filename'] : '';
			$out['extension'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		}

		\blobfolio\common\ref\sanitize::file_extension($out['extension']);

		// Pull the mimes from the extension.
		if (false !== ($ext = static::get_extension($out['extension']))) {
			$out['mime'] = $ext['primary'];
		}

		// Try to read the magic mime, if possible.
		try {
			// Find the real path, if possible.
			if (false !== ($path = realpath($path))) {
				$out['path'] = $path;
				$out['dirname'] = dirname($path);
			}

			// Lookup magic mime, if possible.
			if (
				false !== $path &&
				function_exists('finfo_file') &&
				defined('FILEINFO_MIME_TYPE') &&
				is_file($path)
			) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$magic_mime = \blobfolio\common\sanitize::mime(finfo_file($finfo, $path));
				finfo_close($finfo);
				if (
					$magic_mime &&
					static::MIME_DEFAULT !== $magic_mime &&
					(static::MIME_DEFAULT === $out['mime'] || !preg_match('/^text\//', $magic_mime)) &&
					!static::check_ext_and_mime($out['extension'], $magic_mime)
				) {
					// If we have an alternative magic mime and it is legit,
					// it should override what we derived from the name.
					if (false !== ($mime = static::get_mime($magic_mime))) {
						$out['mime'] = $magic_mime;
						$out['extension'] = $mime['ext'][0];
						foreach ($mime['ext'] as $ext) {
							$out['suggested_filename'][] = "{$out['filename']}.$ext";
						}
					}
				}
			}
		} catch (\Throwable $e) {
			return $out;
		}

		return $out;
	}

	// --------------------------------------------------------------------- end public data access
}



