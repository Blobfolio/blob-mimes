<?php
/**
 * Lord of the Files - MIMEs
 *
 * MIME and filetype management.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

class mime {
	/**
	 * Return MIME aliases for a particular file extension.
	 *
	 * @see {https://www.iana.org/assignments/media-types}
	 * @see {https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types}
	 * @see {http://hg.nginx.org/nginx/raw-file/default/conf/mime.types}
	 * @see {https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in}
	 * @see {https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml}
	 * @see {https://github.com/Blobfolio/blob-mimes}
	 *
	 * @param string $ext File extension.
	 * @return array|bool MIME types or false.
	 */
	public static function get_aliases($ext='') {
		$ext = trim(strtolower($ext));
		$ext = ltrim($ext, '.');
		if (strlen($ext) && array_key_exists($ext, mime\aliases::$data)) {
			$match = mime\aliases::$data[$ext];
		} else {
			$match = false;
		}

		// Filter.
		return apply_filters('blobmimes_get_mime_aliases', $match, $ext);
	}

	/**
	 * Assign a new extension to a filename.
	 *
	 * @param string $filename The original filename.
	 * @param string $ext The new extension.
	 * @return string The renamed file.
	 */
	public static function update_filename_extension($filename, $ext) {
		$ext = strtolower($ext);
		$ext = rtrim($ext, '.');
		$ext = ltrim($ext, '.');

		$filename_parts = explode('.', $filename);

		// Remove the old extension.
		if (count($filename_parts) > 1) {
			array_pop($filename_parts);
		}

		// Add the new extension.
		if (strlen($ext)) {
			$filename_parts[] = $ext;
		}

		return implode('.', $filename_parts);
	}

	/**
	 * Check extension and MIME pairing.
	 *
	 * @see { https://github.com/php/php-src/blob/3ef069d26c7863b059325b9a0d26cac31c97fe4b/ext/fileinfo/libmagic/readcdf.c }
	 *
	 * @param string $ext File extension.
	 * @param string $mime MIME type.
	 * @return bool True/false.
	 */
	public static function check_alias($ext='', $mime='') {
		// Standardize inputs.
		$mime = strtolower(sanitize_mime_type($mime));
		$ext = trim(strtolower($ext));
		$ext = ltrim($ext, '.');

		// Can't continue if the extension is not in the database.
		if (false === ($mimes = static::get_aliases($ext))) {
			// Filter.
			return apply_filters('blobmimes_check_mime_alias', false, $ext, $mime);
		}

		// Before looking for matches, convert any generic CDFV2
		// types into an equally generic, but less variable type.
		if (0 === strpos($mime, 'application/cdfv2')) {
			$mime = 'application/vnd.ms-office';
		}

		// "Default" MIME might not need to trigger a failure.
		if ('application/octet-stream' === $mime) {
			// Filter.
			if (false === apply_filters('blobmimes_check_application_octet_stream', false)) {
				return apply_filters('blobmimes_check_mime_alias', true, $ext, $mime);
			}
		}
		// An empty file doesn't really have a type.
		elseif ('inode/x-empty' === $mime) {
			return apply_filters('blobmimes_check_mime_alias', true, $ext, $mime);
		}

		// Test for the literal MIME, but also certain generic
		// variations like x-subtype and vnd.subtype.
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

		// Overlap is success!
		$found = array_intersect($test, $mimes);
		$match = count($found) > 0;

		// Filter.
		return apply_filters('blobmimes_check_mime_alias', $match, $ext, $mime);
	}

	/**
	 * Check Allowed Aliases
	 *
	 * This will cycle through each allowed ext/MIME pair to see if an
	 * alias matches anything.
	 *
	 * @param string $alias MIME alias.
	 * @param array $mimes Allowed MIME types.
	 * @return array|bool Array containing ext and type keys or false.
	 */
	public static function check_allowed_aliases($alias, $mimes=null) {
		// Default MIMEs.
		if (empty($mimes)) {
			$mimes = get_allowed_mime_types();
		}

		$alias = strtolower(sanitize_mime_type($alias));

		$ext = $type = false;

		// Early bail opportunity.
		if (!$alias || !count($mimes)) {
			return false;
		}

		// Direct hit!
		if (false !== $extensions = array_search($alias, $mimes, true)) {
			$extensions = explode('|', $extensions);
			$ext = $extensions[0];
			$type = $alias;

			return compact('ext', 'type');
		}

		// Try all extensions.
		foreach ($mimes as $extensions=>$mime) {
			$extensions = explode('|', $extensions);
			foreach ($extensions as $extension) {
				if (static::check_alias($extension, $alias)) {
					$ext = $extension;
					$type = $mime;

					return compact('ext', 'type');
				}
			}
		}

		return false;
	}

	/**
	 * Retrieve the "real" file type from the file.
	 *
	 * This extends `wp_check_filetype()` to additionally
	 * consider content-based indicators of a file's
	 * true type.
	 *
	 * The content-based type will override the name-based
	 * type if available and included in the $mimes list.
	 *
	 * A false response will be set if the extension is
	 * not allowed, or if a "real MIME" was found and
	 * that MIME is not allowed.
	 *
	 * @see wp_check_filetype()
	 * @see wp_check_filetype_and_ext()
	 *
	 * @param string $file Full path to the file.
	 * @param string $filename The name of the file (may differ from $file due to $file being in a tmp directory).
	 * @param array  $mimes Optional. Key is the file extension with value as the mime type.
	 * @return array Values with extension first and mime type.
	 */
	public static function check_real_filetype($file, $filename=null, $mimes= null) {
		// Default filename.
		if (empty($filename)) {
			$filename = basename($file);
		}

		// Default MIMEs.
		if (empty($mimes)) {
			$mimes = get_allowed_mime_types();
		}

		// Run a name-based check first.
		$checked = wp_check_filetype($filename, $mimes);

		// Only dig deeper if we can.
		if (
			false !== $checked['ext'] &&
			false !== $checked['type'] &&
			@file_exists($file) &&
			@filesize($file) > 0
		) {
			$real_mime = false;

			try {
				// Fall back to fileinfo, if available.
				if (
					false === $real_mime &&
					extension_loaded('fileinfo') &&
					defined('FILEINFO_MIME_TYPE')
				) {
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$real_mime = finfo_file($finfo, $file);
					finfo_close($finfo);

					// Account for inconsistent return values.
					if (!is_string($real_mime) || !strlen($real_mime)) {
						$real_mime = false;
					}
					else {
						$real_mime = strtolower(sanitize_mime_type($real_mime));
					}
				}
			} catch (\Throwable $e) {
				$real_mime = false;
			} catch (\Exception $e) {
				$real_mime = false;
			}

			// SVGs can be misidentified by fileinfo if they are missing the
			// XML tag and/or DOCTYPE declarations. Most other applications
			// don't have that problem, so let's override fileinfo if the
			// file starts with an opening SVG tag.
			if (
				('image/svg+xml' === $checked['type']) &&
				($real_mime !== $checked['type'])
			) {
				$tmp = @file_get_contents($file);
				if (
					is_string($tmp) &&
					preg_match('/\s*<svg/iu', $tmp)
				) {
					$real_mime = 'image/svg+xml';
				}
			}

			// Evaluate our real MIME.
			if (false !== $real_mime) {
				if (!static::check_alias($checked['ext'], $real_mime)) {
					// Maybe this type belongs to another allowed extension.
					if (false !== $result = static::check_allowed_aliases($real_mime, $mimes)) {
						$checked['ext'] = $result['ext'];
						$checked['type'] = $result['type'];
					}
					// Otherwise reject the results.
					else {
						$checked['ext'] = false;
						$checked['type'] = false;
					}
				}
			}
		}// End content-based type checking.

		// Filter.
		return apply_filters('blobmimes_check_real_filetype', $checked, $file, $filename, $mimes);
	}
}
