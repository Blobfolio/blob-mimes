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

use \blobfolio\common;

class mimes {

	const MIME_DEFAULT = 'application/octet-stream';



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
		return data::BY_MIME;
	}

	/**
	 * Get One MIME
	 *
	 * Return information about a single MIME type.
	 *
	 * @param string $mime MIME type.
	 * @return array MIME data.
	 */
	public static function get_mime($mime = '') {
		common\ref\cast::to_string($mime, true);
		common\ref\sanitize::mime($mime);
		return array_key_exists($mime, data::BY_MIME) ? data::BY_MIME[$mime] : false;
	}

	/**
	 * Get All Extensions
	 *
	 * Return the entire file extension database.
	 *
	 * @return array Extension data.
	 */
	public static function get_extensions() {
		return data::BY_EXT;
	}

	/**
	 * Get One Extension
	 *
	 * Return information about a single file extension.
	 *
	 * @param string $ext File extension.
	 * @return array Extension data.
	 */
	public static function get_extension($ext = '') {
		common\ref\cast::to_string($ext, true);
		common\ref\sanitize::file_extension($ext);
		return array_key_exists($ext, data::BY_EXT) ? data::BY_EXT[$ext] : false;
	}

	/**
	 * Verify a MIME/ext pairing
	 *
	 * @param string $ext File extension.
	 * @param string $mime MIME type.
	 * @param bool $soft Soft pass not-found.
	 * @return bool True.
	 */
	public static function check_ext_and_mime($ext = '', $mime = '', $soft=true) {
		common\ref\cast::to_string($ext, true);
		common\ref\cast::to_string($mime, true);
		common\ref\cast::to_bool($soft, true);

		common\ref\sanitize::file_extension($ext);
		if (!common\mb::strlen($ext)) {
			return false;
		}

		common\ref\sanitize::mime($mime);
		if (!strlen($mime) || ($soft && (static::MIME_DEFAULT === $mime))) {
			return true;
		}

		// Soft pass on extension fail.
		if (false === ($ext = static::get_extension($ext))) {
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
	public static function finfo($path = '', $nice = null) {
		common\ref\cast::to_string($path, true);
		if (!is_null($nice)) {
			common\ref\cast::to_string($nice, true);
		}

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
		common\ref\cast::to_string($path);
		if (
			(false === common\mb::strpos($path, '.')) &&
			(false === common\mb::strpos($path, '/')) &&
			(false === common\mb::strpos($path, '\\'))
		) {
			$out['extension'] = common\sanitize::file_extension($path);
			if (false !== ($ext = static::get_extension($path))) {
				$out['mime'] = $ext['primary'];
			}

			return $out;
		}

		// Path is something path-like.
		common\ref\file::path($path, false);
		$out['path'] = $path;
		$out = common\data::parse_args(pathinfo($path), $out);

		if (!is_null($nice)) {
			$pathinfo = pathinfo($nice);
			$out['filename'] = isset($pathinfo['filename']) ? $pathinfo['filename'] : '';
			$out['extension'] = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		}

		common\ref\sanitize::file_extension($out['extension']);

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
				(false !== $path) &&
				function_exists('finfo_file') &&
				defined('FILEINFO_MIME_TYPE') &&
				@is_file($path)
			) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$magic_mime = common\sanitize::mime(finfo_file($finfo, $path));
				finfo_close($finfo);

				// SVGs can be misidentified by fileinfo if they are missing the
				// XML tag and/or DOCTYPE declarations. Most other applications
				// don't have that problem, so let's override fileinfo if the
				// file starts with an opening SVG tag.
				if (
					('svg' === $out['extension']) &&
					('image/svg+xml' !== $magic_mime)
				) {
					$tmp = @file_get_contents($path);
					if (
						is_string($tmp) &&
						preg_match('/\s*<svg/iu', $tmp)
					) {
						$magic_mime = 'image/svg+xml';
					}
				}

				// Okay, now we can look at the magic in closer detail.
				if (
					$magic_mime &&
					(static::MIME_DEFAULT !== $magic_mime) &&
					(
						(static::MIME_DEFAULT === $out['mime']) ||
						!preg_match('/^text\//', $magic_mime)
					) &&
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
		} catch (\Exception $e) {
			return $out;
		}

		return $out;
	}

	// --------------------------------------------------------------------- end public data access
}



