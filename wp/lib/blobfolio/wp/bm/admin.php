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

	protected static $version;
	protected static $remote_version;
	protected static $remote_home;

	/**
	 * Register Actions and Filters
	 *
	 * @return void Nothing.
	 */
	public static function init() {
		$class = get_called_class();

		// Admin menu entry for upload debugging.
		add_action('admin_menu', array($class, 'menu_debug'));

		// Override upload file validation (general).
		add_filter('wp_check_filetype_and_ext', array($class, 'check_filetype_and_ext'), 10, 4);

		// Override upload file validation (SVG).
		add_filter('wp_check_filetype_and_ext', array($class, 'check_filetype_and_ext_svg'), 15, 4);

		// Set up translations.
		add_action('plugins_loaded', array($class, 'localize'));

		// Update check on debug page.
		add_action('admin_notices', array($class, 'debug_notice'));
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
		if (!static::has_update()) {
			require(BLOBMIMES_BASE_PATH . 'admin/debug.php');
		}
		else {
			echo '<div class="wrap"><span></span><h2>' . esc_html__('Debug File Validation', 'blob-mimes') . '</h2></div>';
		}
	}

	/**
	 * Get Plugin Version
	 *
	 * @return string Version.
	 */
	public static function get_version() {
		if (is_null(static::$version)) {
			$plugin_data = get_plugin_data(BLOBMIMES_INDEX, false, false);
			if (isset($plugin_data['Version'])) {
				static::$version = $plugin_data['Version'];
			}
			else {
				static::$version = '0.0';
			}
		}

		return static::$version;
	}

	/**
	 * Get Remote Version
	 *
	 * @return string Version.
	 */
	public static function get_remote_version() {
		if (is_null(static::$remote_version)) {
			$response = plugins_api(
				'plugin_information',
				array('slug'=>'blob-mimes')
			);
			if (
				!is_wp_error($response) &&
				is_a($response, 'stdClass') &&
				isset($response->version)
			) {
				static::$remote_version = $response->version;
				static::$remote_home = $response->homepage;
			}
			else {
				static::$remote_version = '0.0';
			}
		}

		return static::$remote_version;
	}

	/**
	 * Debug Notice
	 *
	 * This adds a notice to the file debug page, either informing users
	 * that they need to update the plugin, or explaining the page's
	 * purpose.
	 *
	 * @return void Nothing.
	 */
	public static function debug_notice() {
		// We only want to generate a notice if someone is viewing the
		// file debug page, and then only if they didn't just upload a
		// file.
		$screen = get_current_screen();
		if (
			('POST' !== getenv('REQUEST_METHOD')) &&
			('tools_page_blob-mimes-admin' === $screen->id)
		) {
			// Update notice takes priority.
			if (static::has_update()) {
				if (BLOBMIMES_MUST_USE) {
					$update_link = '<a href="' . static::$remote_home . '" target="_blank" rel="noopener">' . esc_html__('update', 'blob-mimes') . '</a>';
				}
				else {
					$update_link = '<a href="' . admin_url('update-core.php') . '">' . esc_html__('update', 'blob-mimes') . '</a>';
				}

				// Update warning.
				$notice_type = 'warning';
				$notice = sprintf(
					esc_html__('Please %s %s to the latest release (%s) before debugging upload-related issues.', 'blob-mimes'),
					$update_link,
					'<em>Lord of the Files</em>',
					'<code>' . static::get_remote_version() . '</code>'
				);

			}
			// Otherwise explain the page's purpose.
			else {
				$notice_type = 'info';
				$notice = esc_html__('If a file has been rejected from the Media Library for "security reasons", use the form below to find out more information.', 'blob-mimes');
			}

			echo '<div class="notice notice-' . $notice_type . '"><p>' . $notice . '</p></div>';
		}
	}

	/**
	 * Has Update?
	 *
	 * We need to query the API because WordPress won't check for
	 * updates if this plugin is installed as Must-Use.
	 *
	 * @return bool True/false.
	 */
	public static function has_update() {
		require_once(trailingslashit(ABSPATH) . 'wp-admin/includes/plugin.php');
		require_once(trailingslashit(ABSPATH) . 'wp-admin/includes/plugin-install.php');
		return (version_compare(static::get_version(), static::get_remote_version()) < 0);
	}

	/**
	 * Override Upload File Validation
	 *
	 * This hooks into wp_check_filetype_and_ext() to improve its
	 * determinations.
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
	 * This is triggered after our general content-based fixer, so if
	 * something is claiming to be an SVG here, it should actually be
	 * one.
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
