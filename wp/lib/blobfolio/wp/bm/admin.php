<?php
/**
 * Lord of the Files - Admin hooks.
 *
 * This covers integrations and behavioral overrides.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

class admin {

	/**
	 * Register Actions and Filters
	 *
	 * @return void Nothing.
	 */
	public static function init() {
		// Admin menu entry for upload debugging.
		add_action('admin_menu', array(get_called_class(), 'menu_debug'));

		// Override upload file validation (general).
		add_filter('wp_check_filetype_and_ext', array(get_called_class(), 'check_filetype_and_ext'), 10, 4);

		// Override upload file validation (SVG).
		add_filter('wp_check_filetype_and_ext', array(get_called_class(), 'check_filetype_and_ext_svg'), 15, 4);

		// Set up translations.
		add_action('plugins_loaded', array(get_called_class(), 'localize'));
	}

	/**
	 * Localize
	 *
	 * @return void Nothing.
	 */
	public static function localize() {
		if (BLOBMIMES_MUST_USE) {
			load_muplugin_textdomain('blob-mimes', basename(BLOBMIMES_BASE_PATH) . '/languages');
		}
		else {
			load_plugin_textdomain('blob-mimes', false, basename(BLOBMIMES_BASE_PATH) . '/languages');
		}
	}

	/**
	 * Menu: Upload Debugger
	 *
	 * @return void Nothing.
	 */
	public static function menu_debug() {
		add_submenu_page(
			'tools.php',
			__('Debug File Validation', 'blob-mimes'),
			__('Debug File Validation', 'blob-mimes'),
			'manage_options',
			'blob-mimes-admin',
			array(get_called_class(), 'page_debug')
		);
	}

	/**
	 * Page: Upload Debugger
	 *
	 * @return void Nothing.
	 */
	public static function page_debug() {
		require_once(BLOBMIMES_BASE_PATH . 'admin/debug.php');
	}

	/**
	 * Override Upload File Validation
	 *
	 * This hooks into wp_check_filetype_and_ext() to improve
	 * its determinations.
	 *
	 * @see wp_check_filetype_and_ext()
	 *
	 * @param array $checked Previous check status.
	 * @param string $file File path.
	 * @param string $filename File name.
	 * @param array $mimes Mimes.
	 * @return array Checked status.
	 */
	public static function check_filetype_and_ext($checked, $file, $filename, $mimes) {
		// We don't care what WP has already done.
		$proper_filename = false;

		// Do basic extension validation and MIME mapping.
		$wp_filetype = mime::check_real_filetype($file, $filename, $mimes);
		$ext = $wp_filetype['ext'];
		$type = $wp_filetype['type'];

		// We can't do any further validation without a file to work with.
		if (!@file_exists($file)) {
			return compact('ext', 'type', 'proper_filename');
		}

		// If the type is valid, should we be renaming the file?
		if (false !== $ext && false !== $type) {
			$new_filename = mime::update_filename_extension($filename, $ext);
			if ($filename !== $new_filename) {
				$proper_filename = $new_filename;
			}
		}

		return compact('ext', 'type', 'proper_filename');
	}

	/**
	 * Sanitize SVG Uploads
	 *
	 * This is triggered after our general content-based
	 * fixer, so if something is claiming to be an SVG
	 * here, it should actually be one.
	 *
	 * @see wp_check_filetype_and_ext()
	 *
	 * @param array $checked Previous check status.
	 * @param string $file File path.
	 * @param string $filename File name.
	 * @param array $mimes Mimes.
	 * @return array Checked status.
	 */
	public static function check_filetype_and_ext_svg($checked, $file, $filename, $mimes) {
		// Only need to do something if the type is SVG.
		if ('image/svg+xml' === $checked['type']) {
			try {
				$contents = @file_get_contents($file);
				$contents = svg::sanitize($contents);

				// Overwrite the contents if we're good.
				if (is_string($contents) && $contents) {
					@file_put_contents($file, $contents);

					// In case it got renamed somewhere along the way.
					if ($checked['proper_filename']) {
						$checked['proper_filename'] = mime::update_filename_extension($checked['proper_filename'], '.svg');
					}
				}
				// Otherwise just fail the download.
				else {
					$checked['type'] = $checked['ext'] = false;
				}
			} catch (\Throwable $e) {
				error_log($e->getMessage());
				$checked['type'] = $checked['ext'] = false;
			} catch (\Exception $e) {
				$checked['type'] = $checked['ext'] = false;
			}
		}

		return $checked;
	}
}
