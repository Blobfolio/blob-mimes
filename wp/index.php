<?php
/**
 * Lord of the Files: Enhanced Upload Security
 *
 * @package blob-mimes
 * @version 0.8.9
 *
 * @see {https://core.trac.wordpress.org/ticket/39963}
 * @see {https://core.trac.wordpress.org/ticket/40175}
 * @see {https://github.com/Blobfolio/blob-mimes/tree/master/wp}
 * @see {https://github.com/Blobfolio/blob-mimes}
 *
 * @wordpress-plugin
 * Plugin Name: Lord of the Files: Enhanced Upload Security
 * Plugin URI: https://wordpress.org/plugins/blob-mimes/
 * Description: This plugin expands file-related security during the upload process.
 * Version: 0.8.9
 * Text Domain: blob-mimes
 * Domain Path: /languages/
 * Author: Blobfolio, LLC
 * Author URI: https://blobfolio.com/
 * License: WTFPL
 * License URI: http://www.wtfpl.net/
 */

// phpcs:disable SlevomatCodingStandard.Namespaces

/**
 * Do not execute this file directly.
 */
if (! defined('ABSPATH')) {
	exit;
}



// Constants.
define('BLOBMIMES_BASE_PATH', dirname(__FILE__) . '/');
define('BLOBMIMES_INDEX', __FILE__);

// Is this installed as a Must-Use plugin?
$blobmimes_must_use = (
	defined('WPMU_PLUGIN_DIR') &&
	@is_dir(WPMU_PLUGIN_DIR) &&
	(0 === strpos(BLOBMIMES_BASE_PATH, WPMU_PLUGIN_DIR))
);
define('BLOBMIMES_MUST_USE', $blobmimes_must_use);



// This requires PHP 5.4+.
if (version_compare(PHP_VERSION, '5.4.0') < 0) {
	/**
	 * Localize Plugin
	 *
	 * @return void Nothing.
	 */
	function blobmimes_localize() {
		if (BLOBMIMES_MUST_USE) {
			load_muplugin_textdomain('blob-mimes', basename(BLOBMIMES_BASE_PATH) . '/languages');
		}
		else {
			load_plugin_textdomain('blob-mimes', false, basename(BLOBMIMES_BASE_PATH) . '/languages');
		}
	}
	add_action('plugins_loaded', 'blobmimes_localize');

	/**
	 * Deactivate Plugin
	 *
	 * @return void Nothing.
	 */
	function blobmimes_deactivate() {
		require_once trailingslashit(ABSPATH) . 'wp-admin/includes/plugin.php';
		deactivate_plugins(plugin_basename(__FILE__));
	}
	add_action('admin_init', 'blobmimes_deactivate');

	/**
	 * Admin Notice
	 *
	 * @return void Nothing.
	 */
	function blobmimes_notice() {
		?>
		<div class="error"><p><?php echo sprintf(esc_html__('%s requires PHP 5.4 or greater. It has been automatically deactivated for you.', 'blob-mimes'), '<strong>Lord of the Files</strong>'); ?></p></div>
		<?php
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}
	}
	add_action('admin_notices', 'blobmimes_notice');

	// And leave before we load anything fun.
	return;
}



// Everyone else gets the goods.
require BLOBMIMES_BASE_PATH . 'bootstrap.php';
