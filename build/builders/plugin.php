<?php
/**
 * Compile Plugin
 *
 * This will update dependencies, optimize the autoloader, and
 * optionally generate a new release zip.
 *
 * @package blobfolio/mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\dev;

use \blobfolio\bob\io;
use \blobfolio\bob\log;
use \blobfolio\common\file as v_file;

class plugin extends \blobfolio\bob\base\mike_wp {
	// Project Name.
	const NAME = 'Lord of the Files';
	const DESCRIPTION = 'Lord of the Files adds content-based validation and sanitizing to WordPress file uploads, making sure that files are what they say they are and safe for inclusion on your site.';
	const CONFIRMATION = '';
	const SLUG = 'blob-mimes';

	const USE_COMPOSER = false;
	const USE_GRUNT = '';

	const RELEASE_TYPE = 'copy';



	/**
	 * Get Shitlist
	 *
	 * @return array Shitlist.
	 */
	protected static function get_shitlist() {
		$shitlist = io::SHITLIST;
		$shitlist [] = '#/bin$#';
		$shitlist [] = '#/tests$#';
		return $shitlist;
	}

	/**
	 * Get Source Directory
	 *
	 * This should be a path to the main plugin root.
	 *
	 * @return string Source.
	 */
	protected static function get_plugin_dir() {
		return dirname(BOB_ROOT_DIR) . '/wp/';
	}

	/**
	 * Get Release Path
	 *
	 * When building a zip, the path should end in .zip. When copying,
	 * it should be an empty directory.
	 *
	 * @return string Source.
	 */
	protected static function get_release_path() {
		return dirname(BOB_ROOT_DIR) . '/' . static::SLUG . '/';
	}
}
