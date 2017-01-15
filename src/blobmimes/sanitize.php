<?php
//---------------------------------------------------------------------
// Sanitization help
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

class sanitize {

	//-------------------------------------------------
	// String
	//
	// Typecasts a value to a string, and ensures it is
	// valid UTF-8.
	//
	// @param str
	// @return str
	public static function string($str='') {
		try {
			$str = (string) $str;
		} catch (\Throwable $e) {
			$str = '';
		} catch (\Exception $e) {
			$str = '';
		}

		//force UTF-8
		if (class_exists('\ForceUTF8\Encoding')) {
			$str = \ForceUTF8\Encoding::toUTF8($str);
			$str = (1 === @preg_match('/^./us', $str)) ? $str : '';
		}
		//try WordPress
		elseif (function_exists('wp_check_invalid_utf8')) {
			$str = wp_check_invalid_utf8($str);
		}
		//try iconv
		elseif (function_exists('iconv') && function_exists('mb_detect_encoding')) {
			$str = iconv(mb_detect_encoding($str, mb_detect_order(), true), 'UTF-8', $str);
			$str = (1 === @preg_match('/^./us', $str)) ? $str : '';
		}
		//leave it alone...

		return $str;
	}

	//-------------------------------------------------
	// Strtolower
	//
	// prefer multi-byte strtolower, but fallback if
	// mbstring is missing
	//
	// @param string
	// @return lowercase
	public static function strtolower($str='') {
		$str = \blobmimes\sanitize::string($str);

		if (function_exists('mb_strtolower')) {
			return mb_strtolower($str, 'UTF-8');
		}
		else {
			return strtolower($str);
		}
	}

	//-------------------------------------------------
	// Path
	//
	// Fix slashes, directories get trailing slash,
	// ensure path exists, etc.
	//
	// @param path
	// @param validate
	// @return path or false
	public static function path($str='', $validate=false) {
		$str = static::string($str);

		if (!\blobmimes\base::strlen($str)) {
			return false;
		}

		//unix or maybe a URL
		if (DIRECTORY_SEPARATOR === '/') {
			$str = str_replace('\\', '/', $str);
		}
		else {
			$str = str_replace('/', '\\', $str);
		}

		//does it exist?
		try {
			if ($validate && false === $str = realpath($str)) {
				return false;
			}
		} catch (\Throwable $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}

		//strip leading slash
		$str = rtrim($str, DIRECTORY_SEPARATOR);

		try {
			if (is_dir($str)) {
				$str .= DIRECTORY_SEPARATOR;
			}
		} catch (\Throwable $e) {
			return $str;
		} catch (\Exception $e) {
			return $str;
		}

		return $str;
	}

}

?>