<?php
//---------------------------------------------------------------------
// WordPress wrappers
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

class wordpress {

	protected static $by_mime;
	protected static $by_ext;
	protected static $raw;

	//-------------------------------------------------
	// Has WordPress?
	//
	// @param n/a
	// @return true/false
	public static function has_wordpress() {
		return defined('ABSPATH') && function_exists('wp_check_invalid_utf8');
	}

	//-------------------------------------------------
	// Load raw MIME data
	//
	// @param n/a
	// @return true/false
	protected static function load_raw() {
		if (!is_array(static::$raw)) {
			if (function_exists('wp_get_mime_types')) {
				static::$raw = wp_get_mime_types();
			}
			else {
				static::$raw = array();
			}
		}

		return true;
	}

	//-------------------------------------------------
	// Get WordPress MIMEs
	//
	// @param n/a
	// @return mimes
	public static function get_mimes() {
		if (!is_array(static::$by_mime)) {
			static::load_raw();
			static::$by_mime = array();

			if (count(static::$raw)) {
				foreach (static::$raw as $k=>$v) {
					if (!isset(static::$by_mime[$v])) {
						static::$by_mime[$v] = array(
							'mime'=>$v,
							'ext'=>array(),
							'source'=>array('WordPress')
						);
					}

					$exts = explode('|', $k);
					foreach ($exts as $ext) {
						if (!in_array($ext, static::$by_mime[$v]['ext'])) {
							static::$by_mime[$v]['ext'][] = $ext;
						}
					}
				}

				ksort(static::$by_mime);
			}
		}

		return static::$by_mime;
	}

	//-------------------------------------------------
	// Get WordPress extensions
	//
	// @param n/a
	// @return mimes
	public static function get_extensions() {
		if (!is_array(static::$by_ext)) {
			static::load_raw();
			static::$by_ext = array();

			if (count(static::$raw)) {
				foreach (static::$raw as $k=>$v) {
					$exts = explode('|', $k);
					foreach ($exts as $ext) {
						$ext = \blobmimes\sanitize::extension($ext);
						$v = \blobmimes\sanitize::mime($v);

						if (!isset(static::$by_ext[$ext])) {
							static::$by_ext[$ext] = array(
								'ext'=>$ext,
								'mime'=>array(),
								'source'=>array('WordPress'),
								'alias'=>array(),
								'primary'=>''
							);
						}

						if (!in_array($v, static::$by_ext[$ext]['mime'])) {
							static::$by_ext[$ext]['mime'][] = $v;
						}

						if (!static::$by_ext[$ext]['primary']) {
							static::$by_ext[$ext]['primary'] = $v;
						}
					}
				}

				ksort(static::$by_ext);
			}
		}

		return static::$by_ext;
	}
}

?>