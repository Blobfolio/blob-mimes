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

	// The WP.org API URL for getting contributor information.
	const PLUGIN_API = 'https://api.wordpress.org/plugins/info/1.0/%s.json?fields[contributors]=1&fields[sections]=0&fields[downloaded]=0&fields[downloadlink]=0&fields[last_updated]=0&fields[tags]=0&fields[tested]=0&fields[homepage]=0&fields[rating]=0&fields[ratings]=0&fields[requires]=0';

	protected static $version;
	protected static $remote_version;
	protected static $remote_home;



	// -----------------------------------------------------------------
	// Init
	// -----------------------------------------------------------------

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

		// AJAX hook for disabling contributor lookups.
		add_action('wp_ajax_bm_ajax_disable_contributor_notice', array($class, 'disable_contributor_notice'));

		// And the corresponding warnings.
		add_filter('admin_notices', array($class, 'contributors_changed_notice'));

		// Pull remote contributor information.
		$next = wp_next_scheduled('cron_get_remote_contributors');
		if ('disabled' === get_option('bm_contributor_notice', false)) {
			// Make sure the CRON job is disabled.
			if ($next) {
				wp_unschedule_event($next, 'cron_get_remote_contributors');
			}
		}
		else {
			add_action('cron_get_remote_contributors', array($class, 'cron_get_remote_contributors'));
			if (!$next) {
				wp_schedule_event(time(), 'hourly', 'cron_get_remote_contributors');
			}
		}

		// Register plugins page quick links if we aren't running in
		// Must-Use mode.
		if (!BLOBMIMES_MUST_USE) {
			add_filter('plugin_action_links_' . plugin_basename(BLOBMIMES_INDEX), array($class, 'plugin_action_links'));
		}
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
	 * Plugin Action Links
	 *
	 * Add a few links to the plugins page so people can more easily
	 * find what they're looking for.
	 *
	 * @param array $links Links.
	 * @return array Links.
	 */
	public static function plugin_action_links($links) {
		if (current_user_can('manage_options')) {
			$links[] = '<a href="' . esc_url(admin_url('tools.php?page=blob-mimes-admin')) . '">' . __('Debug File Validation', 'blob-mimes') . '</a>';
		}

		$links[] = '<a href="https://github.com/Blobfolio/blob-mimes/tree/master/wp" target="_blank" rel="noopener">' . esc_html__('Documentation', 'blob-mimes') . '</a>';

		return $links;
	}

	// ----------------------------------------------------------------- end init



	// -----------------------------------------------------------------
	// Upload Debugger
	// -----------------------------------------------------------------

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

	// ----------------------------------------------------------------- end upload debugger



	// -----------------------------------------------------------------
	// Update Checking (MU)
	// -----------------------------------------------------------------

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

	// ----------------------------------------------------------------- end self updates



	// -----------------------------------------------------------------
	// File Validation
	// -----------------------------------------------------------------

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

	// ----------------------------------------------------------------- end files



	// -----------------------------------------------------------------
	// Plugin Contributor Monitoring
	// -----------------------------------------------------------------

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
		$transient_key = 'bm_local_contrib_' . md5("$slug|$version");
		if (false !== ($contributors = get_transient($transient_key))) {
			return $contributors;
		}

		// The directory in question.
		$dir = trailingslashit(WP_PLUGIN_DIR) . $slug;
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
			set_transient($transient_key, $contributors, WEEK_IN_SECONDS);
		}
		// We should still cache a negative lookup because if we
		// couldn't get the info this time, we probably won't next time.
		else {
			set_transient($transient_key, array(), WEEK_IN_SECONDS);
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

		// Make sure we've run remote checks before.
		if (
			(false === ($remote = get_option('bm_remote_contributors', false))) ||
			!count($remote)
		) {
			return;
		}

		// Make sure updates are checked.
		wp_update_plugins();
		$updates = get_site_transient('update_plugins');
		if (
			!isset($updates->response, $updates->checked) ||
			!is_array($updates->response) ||
			!count($updates->response)
		) {
			return;
		}

		// Look for any local/remote changes.
		$warnings = array();
		foreach ($updates->response as $k=>$v) {
			$slug = $v->slug;

			// Skip this plugin if there were no updates or remote data.
			if (!isset($updates->checked[$k], $remote[$slug])) {
				continue;
			}

			// Pull the local contributor data.
			$version = $updates->checked[$k];
			$local = static::get_local_contributors($slug, $version);
			if (!count($local)) {
				continue;
			}

			// Note the differences.
			$added = array_diff($remote[$slug], $local);
			$removed = array_diff($local, $remote[$slug]);
			if (count($added) || count($removed)) {
				$warnings[$slug] = array(
					'new'=>$added,
					'removed'=>$removed,
				);
			}
		}

		// Nothing to do?
		if (!count($warnings)) {
			return;
		}

		// Let's build some HTML for the output.
		foreach ($warnings as $k=>$v) {
			$tmp = array();

			foreach ($v['removed'] as $v2) {
				$tmp[] = '<span class="wp-ui-text-notification"><span class="dashicons dashicons-minus"></span> ' . esc_html($v2) . '</span>';
			}

			foreach ($v['new'] as $v2) {
				$tmp[] = '<span class="wp-ui-text-highlight"><span class="dashicons dashicons-plus"></span> ' . esc_html($v2) . '</span>';
			}

			$warnings[$k] = '<tr>
				<th scope="row" style="text-align: left; padding-right: 1em; vertical-align: middle;"><a href="' . esc_url("https://wordpress.org/plugins/{$k}/") . '" target="_blank" rel="noopener">' . esc_html($k) . '</a>:</th>
				<td style="vertical-align: middle;">' . implode('&nbsp;&nbsp;&nbsp;&nbsp; ', $tmp) . '</td>
			</tr>';
		}

		// Print the notice!
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

	/**
	 * Extract Plugin Slug From Path
	 *
	 * @param string $path Path.
	 * @return string|bool Slug or false.
	 */
	public static function get_plugin_slug_by_path($path) {
		if (is_string($path) && $path) {
			$slug = $path;
			while (false !== strpos($slug, '/')) {
				$slug = dirname($slug);
			}
			$slug = preg_replace('/\.php$/i', '', $slug);

			return $slug ? $slug : false;
		}

		return false;
	}

	/**
	 * Pull Plugin Contributors
	 *
	 * This is a simple CRON job that will pull contributor information
	 * for any installed plugins so we have that data for later
	 * reference.
	 *
	 * @return void Nothing.
	 */
	public static function cron_get_remote_contributors() {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		$plugins = get_plugins();
		$out = array();

		// We'll also keep track of plugins that aren't hosted on
		// WP.org. This might change, so we'll periodically rebuild the
		// list.
		$save_invalid = false;
		if (false === ($invalid = get_transient('bm_external_plugins'))) {
			$save_invalid = true;
			$invalid = array();
		}

		foreach ($plugins as $k=>$v) {
			// Can't check it.
			if (
				false === ($slug = static::get_plugin_slug_by_path($k)) ||
				isset($invalid[$slug])
			) {
				continue;
			}

			// The plugins_api() function does not fully support the
			// API spec, so we have to do this manually.
			$url = sprintf(static::PLUGIN_API, $slug);
			$response = wp_remote_get(
				$url,
				array(
					'timeout'=>3,
				)
			);
			if (200 === wp_remote_retrieve_response_code($response)) {
				$body = wp_remote_retrieve_body($response);
				$body = json_decode($body, true);
				if ($body) {
					// If WP returned an error, note the slug so we can
					// avoid checking it in the future.
					if (isset($body['error'])) {
						$invalid[$slug] = true;
						$save_invalid = true;
						continue;
					}

					if (
						isset($body['contributors']) &&
						is_array($body['contributors']) &&
						count($body['contributors'])
					) {
						ksort($body['contributors']);
						$out[$slug] = array_keys($body['contributors']);
					}
				}
			}
		}

		// Save the contributors.
		update_option('bm_remote_contributors', $out);

		// Save the invalid plugins.
		if ($save_invalid) {
			set_transient('bm_external_plugins', $invalid, DAY_IN_SECONDS);
		}
	}

	// ----------------------------------------------------------------- end contributors
}
