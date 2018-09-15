<?php
/**
 * Lord of the Files - Multibyte Helpers
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

class mb {

	/**
	 * A strpos() wrapper
	 *
	 * @param string  $haystack Haystack.
	 * @param string  $needle Needle.
	 * @param int $offset Offset.
	 * @return int|bool  Return the first occurrence. False on error.
	 */
	public static function strpos($haystack, $needle, $offset=0) {
		if (
			(\seems_utf8($haystack) || \seems_utf8($needle)) &&
			\function_exists('mb_strpos')
		) {
			return \mb_strpos($haystack, $needle, $offset, 'UTF-8');
		}

		return \strpos($haystack, $needle, $offset);
	}


	/**
	 * A strrpos() wrapper
	 *
	 * @param string  $haystack Haystack.
	 * @param string  $needle Needle.
	 * @param int $offset Offset.
	 * @return int|bool  Return the last occurrence. False on error.
	 */
	public static function strrpos($haystack, $needle, $offset=0) {
		if (
			(\seems_utf8($haystack) || \seems_utf8($needle)) &&
			\function_exists('mb_strrpos')
		) {
			return \mb_strrpos($haystack, $needle, $offset, 'UTF-8');
		}

		return \strrpos($haystack, $needle, $offset);
	}


	/**
	 * A strtolower() wrapper
	 *
	 * @param string $str String.
	 * @return string  Return string in lower case.
	 */
	public static function strtolower($str) {
		if (\seems_utf8($str) && \function_exists('mb_strtolower')) {
			return \mb_strtolower($str, 'UTF-8');
		}

		return \strtolower($str);
	}


	/**
	 * A substr() wrapper
	 *
	 * @param string  $str String.
	 * @param string  $start From.
	 * @param int $length To.
	 * @return string|bool  Return the substring. False on failure.
	 */
	public static function substr($str, $start = 0, $length = null) {
		if (\seems_utf8($str) && \function_exists('mb_substr')) {
			return \mb_substr($str, $start, $length, 'UTF-8');
		}

		return \substr($str, $start, $length);
	}

}
