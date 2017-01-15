<?php
//---------------------------------------------------------------------
// Base tools
//---------------------------------------------------------------------
// blob-mimes v0.5
// https://github.com/Blobfolio/blob-mimes
//
// REQUIREMENTS:
//   -- PHP 5.3.2
//   -- JSON
//
// OPTIONAL:
//   -- MBSTRING
//   -- FINFO
//   -- WordPress
//
// Copyright © 2017  Blobfolio, LLC  (email: hello@blobfolio.com)
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

namespace blobmimes;

class base {

	//our data
	protected static $by_mime;
	protected static $by_ext;

	const BUILD_MESSAGE = 'Run build/build.php and copy the generated file to src/.';
	const BY_MIME_FILE = 'extensions_by_mime.json';
	const BY_EXT_FILE = 'mimes_by_extension.json';
	const MIME_DEFAULT = 'application/octet-stream';

	//-------------------------------------------------
	// Strlen
	//
	// prefer multi-byte strlen, but fallback if
	// mbstring is missing
	//
	// @param string
	// @return length
	public static function strlen($str='') {
		$str = \blobmimes\sanitize::string($str);

		if (function_exists('mb_strlen')) {
			return (int) mb_strlen($str, 'UTF-8');
		}
		else {
			return (int) strlen($str);
		}
	}

	//-------------------------------------------------
	// Load JSON
	//
	// load JSON from a file
	//
	// @param path
	// @return JSON or Exception
	protected static function load_json($path='') {
		$path = dirname(dirname(__FILE__)) . "/$path";
		$path = \blobmimes\sanitize::path($path, true);

		if (!is_file($path)) {
			throw new \Exception('Invalid JSON path: ' . $path);
		}

		$data = \blobmimes\sanitize::string(@file_get_contents($path));
		$data = json_decode($data, true);
		if (!is_array($data)) {
			throw new \Exception('Invalid JSON data: ' . $path);
		}

		return $data;
	}

	//-------------------------------------------------
	// Get MIME entry
	//
	// @param mime
	// @return data or false
	public static function get_mime($mime='') {
		$mime = \blobmimes\sanitize::string($mime);

		if (!is_array(static::$by_mime)) {
			if (false === static::$by_mime = static::load_json(static::BY_MIME_FILE)) {
				throw new \Exception('Could not load MIME database. ' . static::BUILD_MESSAGE);
			}

			//merge WP results
			if (\blobmimes\wordpress::has_wordpress()) {
				$data = \blobmimes\wordpress::get_mimes();
				foreach ($data as $k=>$v) {
					if (!isset(static::$by_mime[$k])) {
						static::$by_mime[$k] = $v;
					}
					else {
						foreach ($v['ext'] as $ext) {
							if (!in_array($ext, static::$by_mime[$k]['ext'])) {
								static::$by_mime[$k]['ext'][] = $ext;
							}
						}
						static::$by_mime[$k]['source'][] = 'WordPress';
					}
				}
			}
		}

		return isset(static::$by_mime[$mime]) ? static::$by_mime[$mime] : false;
	}

	//-------------------------------------------------
	// Get extension entry
	//
	// @param ext
	// @return data or false
	public static function get_ext($ext='') {
		$ext = \blobmimes\sanitize::string($ext);

		if (!is_array(static::$by_ext)) {
			if (false === static::$by_ext = static::load_json(static::BY_EXT_FILE)) {
				throw new \Exception('Could not load file extension database. ' . static::BUILD_MESSAGE);
			}

			//merge WP results
			if (\blobmimes\wordpress::has_wordpress()) {
				$data = \blobmimes\wordpress::get_extensions();
				foreach ($data as $k=>$v) {
					if (!isset(static::$by_ext[$k])) {
						static::$by_ext[$k] = $v;
					}
					else {
						foreach ($v['mime'] as $mime) {
							if (!in_array($mime, static::$by_ext[$k]['mime'])) {
								static::$by_ext[$k]['mime'][] = $mime;
							}
						}
						static::$by_ext[$k]['source'][] = 'WordPress';
					}
				}
			}
		}

		return isset(static::$by_ext[$ext]) ? static::$by_ext[$ext] : false;
	}
}

?>