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
	protected static $checked_plugins_contributors;
	protected static $changed_plugins_contributors;

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

		// Pull contributor information during update checks.
		add_filter('transient_update_plugins', array($class, 'update_plugins_contributors'), PHP_INT_MAX);
		add_filter('site_transient_update_plugins', array($class, 'update_plugins_contributors'), PHP_INT_MAX);

		// AJAX hook for disabling contributor lookups.
		add_action('wp_ajax_bm_ajax_disable_contributor_notice', array($class, 'disable_contributor_notice'));

		// And the corresponding warnings.
		add_filter('admin_notices', array($class, 'contributors_changed_notice'));
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

	/**
	 * Remote Contributors
	 *
	 * Gather a list of contributors that WP.org currently knows about
	 * for any plugins with updates.
	 *
	 * @param string $slug Plugin slug.
	 * @param string $version Version.
	 * @return array Contributors.
	 */
	protected static function get_remote_contributors($slug, $version) {
		// Obviously bad data.
		if (!$slug || !$version || !is_string($slug) || !is_string($version)) {
			return array();
		}

		// Cache this for each plugin/version pair.
		$transient_key = 'remote_contrib_' . md5("$slug|$version");
		if (false !== ($cache = get_transient($transient_key))) {
			return $cache;
		}

		// We need these.
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');

		$response = plugins_api(
			'plugin_information',
			array(
				'slug'=>$slug,
				// This option does not seem to work as documented, but
				// just in case let's make sure contributors come back
				// in the response.
				'fields'=>array('contributors'=>true),
			)
		);
		if (
			is_wp_error($response) ||
			!isset($response->contributors) ||
			!is_array($response->contributors) ||
			!count($response->contributors)
		) {
			return array();
		}

		// Cache for up to one day.
		$contributors = array_keys($response->contributors);
		sort($contributors);

		set_transient($transient_key, $contributors, 86400);
		return $contributors;
	}

	/**
	 * Local Contributors
	 *
	 * Try to find and parse installed plugins' readme.txt files for
	 * contributor information.
	 *
	 * @param string $slug Plugin slug (index version).
	 * @param string $version Version.
	 * @return array Contributors.
	 */
	protected static function get_local_contributors($slug, $version) {
		// Obviously bad data.
		if (!$slug || !$version || !is_string($slug) || !is_string($version)) {
			return array();
		}

		// Cache this for each plugin/version pair.
		$transient_key = 'local_contrib_' . md5("$slug|$version");
		if (false !== ($cache = get_transient($transient_key))) {
			return $cache;
		}

		// The directory in question.
		$dir = trailingslashit(WP_PLUGIN_DIR) . dirname($slug);
		if (!@is_dir($dir)) {
			return array();
		}

		// If the readme file isn't named normally, we have to run
		// through the directory (but just one level).
		if (
			!@is_file("$dir/readme.txt") ||
			(false === ($contributors = static::parse_readme_contributors($slug)))
		) {
			$files = list_files($dir, 1);
			if (is_array($files)) {
				foreach ($files as $file) {
					if (
						preg_match('/^readme\.(txt|md)$/i', basename($file)) &&
						@is_file($file) &&
						(false !== ($contributors = static::parse_readme_contributors($file)))
					) {
						break;
					}
				}
			}
		}

		// Looking good!
		if (is_array($contributors) && count($contributors)) {
			set_transient($transient_key, $contributors, 86400);
		}
		// We should still cache a negative lookup because if we
		// couldn't get the info this time, we probably won't next time.
		else {
			set_transient($transient_key, array(), 86400);
		}

		return $contributors;
	}

	/**
	 * Parse Readme Contributors
	 *
	 * @param string $file File.
	 * @return array|bool Contributors or false.
	 */
	protected static function parse_readme_contributors($file) {
		if (!$file || !@is_file($file)) {
			return false;
		}

		require_once (ABSPATH . 'wp-admin/includes/file.php');

		// This can actually be parsed the same way plugin index files
		// are. Neat!
		$headers = get_file_data($file, array('Contributors'=>'Contributors'));
		if (isset($headers['Contributors'])) {
			// These are comma-separated, so let's array-ize them and
			// clean it up a bit.
			$headers['Contributors'] = explode(',', $headers['Contributors']);
			foreach ($headers['Contributors'] as $k=>$v) {
				$headers['contributors'][$k] = trim($v);
				if (!$v) {
					unset($headers['contributors'][$k]);
				}
			}

			// We should have at least one!
			if (count($headers['contributors'])) {
				$headers['contributors'] = array_unique($headers['contributors']);
				sort($headers['contributors']);
				return $headers['contributors'];
			}
		}

		// No go.
		return false;
	}

	/**
	 * Add Contributor Info to Updates Data
	 *
	 * @see https://core.trac.wordpress.org/ticket/42255
	 * @see https://meta.trac.wordpress.org/ticket/3207
	 *
	 * @param array $data Data.
	 * @return array Data.
	 */
	public static function update_plugins_contributors($data) {
		// Make sure the data is good and that we are supposed to show
		// this information.
		if (
			!is_object($data) ||
			!isset($data->response, $data->last_checked, $data->checked) ||
			!is_array($data->response) ||
			!count($data->response) ||
			!is_array($data->checked) ||
			!count($data->checked) ||
			($data->last_checked === static::$checked_plugins_contributors) ||
			('disabled' === get_option('bm_contributor_notice', false))
		) {
			return $data;
		}

		// The filters unfortunately trigger about 5 million times in a
		// single run. Haha. We'll use the last-checked timestamp to
		// prevent meddling unnecessarily.
		static::$checked_plugins_contributors = $data->last_checked;
		static::$changed_plugins_contributors = array();

		// Loop through the data to provide contributor data, where
		// possible.
		foreach ($data->response as $k=>$v) {
			// We can skip things we've already done or things that
			// aren't hosted by WordPress.
			if (
				!isset($v->package, $data->checked[$k], $v->slug) ||
				!$v->slug ||
				!preg_match('#^https?://downloads.wordpress.org/#i', $v->package)
			) {
				continue;
			}

			// Current version is kept somewhere else.
			$version = $data->checked[$k];

			// Try to grab remote data.
			$remote = static::get_remote_contributors($v->slug, $version);
			if (!count($remote)) {
				continue;
			}

			// Try to grab local data.
			$local = static::get_local_contributors($k, $version);
			if (!count($local)) {
				continue;
			}

			static::$changed_plugins_contributors[$k] = array(
				'slug'=>$v->slug,
				'new'=>array_diff($remote, $local),
				'removed'=>array_diff($local, $remote),
			);
		}

		return $data;
	}

	/**
	 * Contributor Change Notice
	 *
	 * Warn users if a pending plugin update has different contributors
	 * than the version they currently have installed.
	 *
	 * @see https://core.trac.wordpress.org/ticket/42255
	 * @see https://meta.trac.wordpress.org/ticket/3207
	 *
	 * @return void Nothing.
	 */
	public static function contributors_changed_notice() {
		// Do not display if disabled.
		if ('disabled' === get_option('bm_contributor_notice', false)) {
			return;
		}

		// We only want to display this on the plugins and update pages.
		$screen = get_current_screen();
		if (('update-core' !== $screen->id) && ('plugins' !== $screen->id)) {
			return;
		}

		// Make sure updates were actually checked.
		if (!is_array(static::$changed_plugins_contributors)) {
			wp_update_plugins();
		}

		// We usually won't need to print a notice.
		if (!count(static::$changed_plugins_contributors)) {
			return;
		}

		// Still might not need to be here, but we have to check each
		// update to be sure.
		$warnings = array();
		foreach (static::$changed_plugins_contributors as $v) {
			if (!count($v['new']) && !count($v['removed'])) {
				continue;
			}

			// Proceed with a warning!
			$tmp = array();
			foreach ($v['removed'] as $v2) {
				$tmp[] = '<span class="wp-ui-text-notification"><span class="dashicons dashicons-minus"></span> ' . esc_html($v2) . '</span>';
			}
			foreach ($v['new'] as $v2) {
				$tmp[] = '<span class="wp-ui-text-highlight"><span class="dashicons dashicons-plus"></span> ' . esc_html($v2) . '</span>';
			}

			$warnings[] = '<tr>
				<th scope="row" style="text-align: left; padding-right: 1em; vertical-align: middle;"><a href="' . esc_url("https://wordpress.org/plugins/{$v['slug']}/") . '" target="_blank" rel="noopener">' . esc_html($v['slug']) . '</a>:</th>
				<td style="vertical-align: middle;">' . implode('&nbsp;&nbsp;&nbsp;&nbsp; ', $tmp) . '</td>
			</tr>';
		} // Each update.

		// Provide a way to opt-out of these notices.
		if (count($warnings)) {
			?>
			<div class="notice notice-warning blob-mimes-contributor-notice">
				<?php
				echo '<p>' . sprintf(
					esc_html__('%s The list of contributors for one or more plugins has changed since you last performed updates.', 'blob-mimes'),
					'<strong>' . esc_html__('Warning', 'blob-mimes') . ':</strong>'
				) . ' <a href="javascript: alert(\'' . esc_js(esc_attr__('This can potentially pose a security threat if the new author(s) are not nice people. It is recommended you re-review the code before continuing.', 'blob-mimes')) . '\');" class="dashicons dashicons-editor-help"></a></p>';

				echo '<table style="margin: .5em 0 .5em 2em;"><tbody>' . implode('', $warnings) . '</tbody></table>';

				echo '<p class="description">' . sprintf(
					esc_html__('If you prefer not to receive these notices in the future, click %s.', 'blob-mimes'),
					'<a href="#" class="blob-mimes-contributor-notice--dismiss">' . esc_html__('here', 'blob-mimes') . '</a>'
				) . '</p>';
				?>

				<script>
					jQuery('.blob-mimes-contributor-notice--dismiss').click(function(e) {
						e.preventDefault();

						var notice = jQuery('.blob-mimes-contributor-notice'),
							data = {
								action: 'bm_ajax_disable_contributor_notice',
								n: '<?php echo wp_create_nonce('bm_ajax_disable_contributor_notice');?>'
							};

						notice.css('opacity', .5);

						jQuery.post(
							ajaxurl,
							data,
							function() {
								notice.remove();
							}
						);
					});
				</script>
			</div>
			<?php
		}
	}

	/**
	 * AJAX: Disable Contributor Notice
	 *
	 * Some people may not find the contributor change notices helpful.
	 * This will prevent them from being calculated or displaying.
	 *
	 * @see https://core.trac.wordpress.org/ticket/42255
	 * @see https://meta.trac.wordpress.org/ticket/3207
	 *
	 * @return void Nothing.
	 */
	public static function disable_contributor_notice() {
		if (
			current_user_can('manage_options') &&
			isset($_POST['n']) &&
			wp_verify_nonce($_POST['n'], 'bm_ajax_disable_contributor_notice')
		) {
			update_option('bm_contributor_notice', 'disabled');
			echo 1;
		}
		else {
			echo 0;
		}

		exit;
	}
}
