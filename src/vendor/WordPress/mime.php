<?php
//---------------------------------------------------------------------
// blob-mimes: MIME and file extension helper
//---------------------------------------------------------------------
// blob-mimes v0.5
// https://github.com/Blobfolio/blob-mimes
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

namespace Blobfolio;

class Mime {

	//our data
	protected static $by_mime;
	protected static $by_ext;
	protected static $wp_mimes;

	const BY_MIME_FILE = 'extensions_by_mime.json';
	const BY_EXT_FILE = 'mimes_by_extension.json';
	const MIME_DEFAULT = 'application/octet-stream';

	//---------------------------------------------------------------------
	// Sanitizing
	//---------------------------------------------------------------------

	//-------------------------------------------------
	// Sanitize String
	//
	// sanitize a string
	//
	// @param string
	// @return string
	protected static function sanitize_string( $str = '' ) {
		try {
			$str = (string) $str;
		} catch (\Throwable $e) {
			$str = '';
		} catch (\Exception $e) {
			$str = '';
		}

		$str = wp_check_invalid_utf8( $str );
		return $str;
	}

	//-------------------------------------------------
	// Sanitize Path
	//
	// Fix slashes, directories get trailing slash,
	// ensure path exists, etc.
	//
	// @param path
	// @param validate (for e.g. local file)
	// @return path or false
	protected static function sanitize_path( $str = '', $validate = false ) {
		$str = static::sanitize_string( $str );

		if ( ! strlen( $str ) ) {
			return false;
		}

		//unix or maybe a URL
		if ( DIRECTORY_SEPARATOR === '/' ) {
			$str = str_replace( '\\', '/', $str );
		} else {
			$str = str_replace( '/', '\\', $str );
		}

		//does it exist?
		try {
			if ( $validate && false === $str = realpath( $str ) ) {
				return false;
			}
		} catch (\Throwable $e) {
			return false;
		} catch (\Exception $e) {
			return false;
		}

		//strip leading slash
		$str = rtrim( $str, DIRECTORY_SEPARATOR );

		try {
			if ( is_dir( $str ) ) {
				$str .= DIRECTORY_SEPARATOR;
			}
		} catch (\Throwable $e) {
			return $str;
		} catch (\Exception $e) {
			return $str;
		}

		return $str;
	}

	//-------------------------------------------------
	// Sanitize Extension
	//
	// @param str
	// @return str
	protected static function sanitize_extension( $str = '' ) {
		$str = static::sanitize_string( $str );
		$str = strtolower( $str );
		$str = ltrim( $str, '*.' );
		$str = preg_replace( '/\s/', '', $str );
		return $str;
	}

	//-------------------------------------------------
	// Sanitize MIME Type
	//
	// @param str
	// @return str
	protected static function sanitize_mime( $str = '' ) {
		$str = static::sanitize_string( $str );
		$str = sanitize_mime_type( $str );
		$str = strtolower( $str );
		return $str;
	}

	//--------------------------------------------------------------------- end sanitizing



	//---------------------------------------------------------------------
	// Data Population
	//---------------------------------------------------------------------

	//-------------------------------------------------
	// Load JSON
	//
	// load JSON from a file
	//
	// @param path
	// @return JSON or Exception
	protected static function load_json( $path = '' ) {
		$path = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . "$path";
		$path = static::sanitize_path( $path, true );

		if ( ! is_file( $path ) ) {
			throw new \Exception( 'Invalid JSON path: ' . $path );
		}

		$data = static::sanitize_string( @file_get_contents( $path ) );
		$data = json_decode( $data, true );
		if ( ! is_array( $data ) ) {
			throw new \Exception( 'Invalid JSON data: ' . $path );
		}

		return $data;
	}

	//-------------------------------------------------
	// Load WordPress MIMEs
	//
	// this generates a simple ext=>mimes() array for
	// faster searching
	//
	// @param n/a
	// @return true
	protected static function load_wp_mimes() {
		if ( is_null( static::$wp_mimes ) ) {
			$raw = wp_get_mime_types();
			static::$wp_mimes = array();
			static::get_ext();

			//run through WordPress MIME types
			foreach ( $raw as $k => $mime ) {
				$exts = explode( '|', $k );
				$mime = static::sanitize_mime( $mime );
				foreach ( $exts as $ext ) {
					$ext = static::sanitize_extension( $ext );
					if ( ! strlen( $ext ) ) {
						continue;
					}

					//add the extension if not already present
					if ( ! isset( static::$wp_mimes[ $ext ] ) ) {
						static::$wp_mimes[ $ext ] = array();
					}

					//add the mime if not already present
					if ( ! in_array( $mime, static::$wp_mimes[ $ext ] ) ) {
						static::$wp_mimes[ $ext ][] = $mime;
					}
				}
			}

			ksort( static::$wp_mimes[ $ext ] );
		}

		return true;
	}

	//--------------------------------------------------------------------- end data population



	//---------------------------------------------------------------------
	// Public Data Access
	//---------------------------------------------------------------------

	//-------------------------------------------------
	// Get MIME entry
	//
	// @param mime
	// @return data or false
	public static function get_mime( $mime = '' ) {
		$mime = static::sanitize_mime( $mime );

		if ( ! is_array( static::$by_mime ) ) {
			if ( false === static::$by_mime = static::load_json( static::BY_MIME_FILE ) ) {
				throw new \Exception( 'Could not load MIME database.' );
			}

			//add WordPress values
			static::load_wp_mimes();

			foreach ( static::$wp_mimes as $k => $v ) {
				foreach ( $v as $v2 ) {
					if ( ! isset( static::$by_mime[ $v2 ] ) ) {
						static::$by_mime[ $v2 ] = array(
							'mime' => $v2,
							'ext' => array( $k ),
							'source' => array( 'WordPress' ),
						);
					} else {
						if ( ! in_array( $k, static::$by_mime[ $v2 ]['ext'] ) ) {
							static::$by_mime[ $v2 ]['ext'][] = $k;
						}
						if ( ! in_array( 'WordPress', static::$by_mime[ $v2 ]['source'] ) ) {
							static::$by_mime[ $v2 ]['source'][] = 'WordPress';
						}
					}
				}
			}
		}

		return isset( static::$by_mime[ $mime ] ) ? static::$by_mime[ $mime ] : false;
	}

	//-------------------------------------------------
	// Get extension entry
	//
	// @param ext
	// @return data or false
	public static function get_ext( $ext = '' ) {
		$ext = static::sanitize_extension( $ext );

		if ( ! is_array( static::$by_ext ) ) {
			if ( false === static::$by_ext = static::load_json( static::BY_EXT_FILE ) ) {
				throw new \Exception( 'Could not load file extension database.' );
			}

			//add WordPress values
			static::load_wp_mimes();
			foreach ( static::$wp_mimes as $k => $v ) {
				if ( ! isset( static::$by_ext[ $k ] ) ) {
					static::$by_ext[ $k ] = array(
						'ext' => $k,
						'mime' => $v,
						'source' => array( 'WordPress' ),
						'alias' => array(),
						'primary' => $v[0],
					);
				} else {
					foreach ( $v as $v2 ) {
						//add mime, but prioritize it
						if ( ! in_array( $v2, static::$by_ext[ $k ]['mime'] ) ) {
							array_unshift( static::$by_ext[ $k ]['mime'], $v2 );
						}

						//always use WP's MIME as the primary
						static::$by_ext[ $k ]['primary'] = $v2;

						if ( ! in_array( 'WordPress', static::$by_ext[ $k ]['source'] ) ) {
							static::$by_ext[ $k ]['source'][] = 'WordPress';
						}
					}
				}
			}
		}

		return isset( static::$by_ext[ $ext ] ) ? static::$by_ext[ $ext ] : false;
	}

	//-------------------------------------------------
	// Verify MIME and Extension pair
	//
	// @param ext
	// @param mime
	// @return true/false
	public static function check_ext_and_mime( $ext = '', $mime = '' ) {
		$ext = static::sanitize_extension( $ext );
		if ( ! strlen( $ext ) ) {
			return false;
		}

		//soft pass invalid MIMEs
		$mime = static::sanitize_mime( $mime );
		if ( ! strlen( $mime ) || static::MIME_DEFAULT === $mime ) {
			return true;
		}

		//soft pass on extension fail
		if ( false === $ext = static::get_ext( $ext ) ) {
			return true;
		}

		//loose mime check
		$real = $ext['mime'];
		$test = array( $mime );

		//we want to also look for x-type variants
		$parts = explode( '/', $mime );
		if ( preg_match( '/^x\-/', $parts[ count( $parts ) - 1 ] ) ) {
			$parts[ count( $parts ) - 1 ] = preg_replace( '/^x\-/', '', $parts[ count( $parts ) - 1 ] );
		} else {
			$parts[ count( $parts ) - 1 ] = 'x-' . $parts[ count( $parts ) - 1 ];
		}
		$test[] = implode( '/', $parts );

		//any overlap?
		$found = array_intersect( $real, $test );
		return count( $found ) > 0;
	}

	//-------------------------------------------------
	// Get File Info
	//
	// @param path
	// @param true name, for e.g. tmp uploads
	// @return info or false
	public static function finfo( $path = '', $nice = null ) {
		$out = array(
			'dirname' => '',
			'basename' => '',
			'extension' => '',
			'filename' => '',
			'path' => '',
			'mime' => static::MIME_DEFAULT,
			'suggested_filename' => array(),
		);

		//path might just be an extension
		$path = static::sanitize_string( $path );
		if ( false === strpos( $path, '.' ) &&
			false === strpos( $path, '/' ) &&
			false === strpos( $path, '\\' )
		) {
			$out['extension'] = static::sanitize_extension( $path );
			if ( false !== ($ext = static::get_ext( $path )) ) {
				$out['mime'] = $ext['primary'];
			}

			return $out;
		}

		//path is something path-like
		$path = static::sanitize_path( $path );
		$out['path'] = $path;
		$pathinfo = pathinfo( $path );
		foreach ( $pathinfo as $k => $v ) {
			if ( isset( $out[ $k ] ) ) {
				$out[ $k ] = static::sanitize_string( $v );
			}
		}

		if ( is_string( $nice ) ) {
			$pathinfo = pathinfo( $nice );
			$out['filename'] = $pathinfo['filename'];
			$out['extension'] = $pathinfo['extension'];
		}

		$out['extension'] = static::sanitize_extension( $out['extension'] );

		//pull the mimes from the extension
		if ( false !== ($ext = static::get_ext( $out['extension'] )) ) {
			$out['mime'] = $ext['primary'];
		}

		//try to read the magic mime, if possible
		try {
			//find the real path, if possible
			if ( false !== ($path = realpath( $path )) ) {
				$out['path'] = $path;
				$out['dirname'] = dirname( $path );
			}

			//lookup magic mime, if possible
			if ( false !== $path && function_exists( 'finfo_file' ) && is_file( $path ) ) {
				$finfo = finfo_open( FILEINFO_MIME_TYPE );
				$magic_mime = static::sanitize_mime( finfo_file( $finfo, $path ) );
				if ( $magic_mime &&
					static::MIME_DEFAULT !== $magic_mime &&
					! static::check_ext_and_mime( $out['extension'], $magic_mime )
				) {
					//if we have an alternative magic mime and it is legit,
					//it should override what we derived from the name
					if ( false !== ($mime = static::get_mime( $magic_mime )) ) {
						$out['mime'] = $magic_mime;
						foreach ( $mime['ext'] as $ext ) {
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

	//--------------------------------------------------------------------- end public data access
}


