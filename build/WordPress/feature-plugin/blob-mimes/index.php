<?php
// @codingStandardsIgnoreFile
/**
 * MIME Alias Handling
 *
 * @package blob-mimes
 * @version 0.1.2
 *
 * @see {https://core.trac.wordpress.org/ticket/39963}
 * @see {https://core.trac.wordpress.org/ticket/40175}
 * @see {https://github.com/Blobfolio/blob-mimes/tree/master/build/WordPress}
 * @see {https://github.com/Blobfolio/blob-mimes}
 *
 * @wordpress-plugin
 * Plugin Name: MIME Alias Handling
 * Plugin URI: https://core.trac.wordpress.org/ticket/39963
 * Description: Feature Plugin integrating MIME alias support into the file upload validation process.
 * Version: 0.1.2
 * Author: Blobfolio, LLC
 * Author URI: https://blobfolio.com/
 * License: WTFPL
 * License URI: http://www.wtfpl.net/
 */

/**
 * Do not execute this file directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Disable the plugin if the data file exists natively.
 */

if (
	file_exists( ABSPATH . WPINC . '/media-mimes.php' )
) {
	blob_mimes_deactivate();
}



/**
 * Safety First!
 *
 * Check to make sure this plugin is still needed.
 *
 * @since 0.1.0
 *
 * @return void Nothing.
 */
function blob_mimes_deactivate() {
	// This plugin should be de-activated if any components
	// have landed in the core.
	if (
		file_exists( ABSPATH . WPINC . '/media-mimes.php' ) ||
		function_exists( 'wp_check_real_filetype' ) ||
		function_exists( 'wp_check_mime_alias' )
	) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		deactivate_plugins( dirname( __FILE__ ) . '/index.php' );
	}
	// Otherwise let's pull in the functions!
	else {
		require_once( dirname( __FILE__ ) . '/functions.php' );
	}
}
add_action ( 'plugins_loaded', 'blob_mimes_deactivate', 5, 0 );



// Update handler.
@require_once( dirname( __FILE__ ) . '/functions-updates.php' );
