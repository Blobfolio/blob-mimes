<?php
/**
 * Lord of the Files: Enhanced Upload Security
 *
 * @package blob-mimes
 * @version 0.5.1
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
 * Version: 0.5.1
 * Author: Blobfolio, LLC
 * Author URI: https://blobfolio.com/
 * License: WTFPL
 * License URI: http://www.wtfpl.net/
 */

/**
 * Do not execute this file directly.
 */
if (!defined('ABSPATH')) {
	exit;
}



// Constants.
define('BM_BASE', dirname(__FILE__) . '/');



// This requires PHP 5.4+.
if (version_compare(PHP_VERSION, '5.4.0') < 0) {
	/**
	 * Deactivate Plugin
	 *
	 * @return void Nothing.
	 */
	function blobmimes_deactivate() {
		require_once(trailingslashit(ABSPATH) . 'wp-admin/includes/plugin.php');
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
		<div class="error"><p><strong>Lord of the Files</strong> requires PHP 5.4 or greater. It has been automatically deactivated for you.</p></div>
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
require_once(BM_BASE . 'bootstrap.php');
