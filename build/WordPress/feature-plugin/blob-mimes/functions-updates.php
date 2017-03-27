<?php
// @codingStandardsIgnoreFile
/**
 * MIME Alias Handling: Plugin Updates
 *
 * @package blob-mimes
 * @since 0.1.1
 */

/**
 * Do not execute this file directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Get Plugin Info
 *
 * @since 0.1.1
 *
 * @param string $key Key.
 * @return mixed Requested property (if any), all properties, or false.
 */
function blob_mimes_get_info( $key = null ) {
	static $info;

	if ( is_null( $info ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		$info = get_plugin_data( dirname( __FILE__ ) . '/index.php' );
	}

	// Return what corresponds to $key.
	if ( ! is_null( $key ) ) {
		return array_key_exists( $key, $info ) ? $info[ $key ] : false;
	}

	// Return the whole thing.
	return $info;
}



/**
 * Get Plugin Info (Remote)
 *
 * This will pull the latest information from Github.
 *
 * @since 0.1.1
 *
 * @param string $key Key.
 * @return mixed Requested property (if any), all properties, or false.
 */
function blob_mimes_get_remote_info( $key = null ) {
	static $info;
	$transient_key = 'blob_mimes_remote_info';

	if ( is_null( $info ) && false === $info = get_transient( $transient_key ) ) {
		$info = array();
		$data = wp_remote_get( 'https://github.com/Blobfolio/blob-mimes/raw/master/build/WordPress/feature-plugin/plugin.json' );
		if ( is_array( $data ) && array_key_exists( 'body', $data ) ) {
			try {
				$response = json_decode( $data['body'], true );
				if ( is_array( $response ) ) {
					foreach ( $response as $k => $v ) {
						$info[ $k ] = $v;
					}

					set_transient( $transient_key, $info, 3600 );
				}
			} catch (Exception $e) {
				$info = array();
			}
		}
	}

	// Return what corresponds to $key.
	if ( ! is_null( $key ) ) {
		return array_key_exists( $key, $info ) ? $info[ $key ] : false;
	}

	// Return the whole thing.
	return $info;
}



/**
 * Get Installed Version
 *
 * @since 0.1.1
 *
 * @return string Version.
 */
function blob_mimes_get_installed_version() {
	static $version;

	if ( is_null( $version ) ) {
		$version = (string) blob_mimes_get_info( 'Version' );
	}

	return $version;
}



/**
 * Get Latest Version
 *
 * @since 0.1.1
 *
 * @return string Version.
 */
function blob_mimes_get_latest_version() {
	static $version;

	if ( is_null( $version ) ) {
		$version = (string) blob_mimes_get_remote_info( 'Version' );
	}

	return $version;
}



/**
 * Check Updates
 *
 * @since 0.1.1
 *
 * @param object $option Plugin information.
 * @return string Version.
 */
function blob_mimes_check_update( $option ) {

	// Make sure arguments make sense.
	if ( ! is_object( $option ) ) {
		return $option;
	}

	// Local and remote versions.
	$installed = blob_mimes_get_installed_version();
	$remote = blob_mimes_get_latest_version();

	// Bad data and/or match, nothing to do!
	if ( false === $remote || false === $installed || $remote <= $installed ) {
		return $option;
	}

	// Set up the entry.
	$path = 'blob-mimes/index.php';
	if ( ! array_key_exists( $path, $option->response ) ) {
		$option->response[ $path ] = new stdClass();
	}

	$option->response[ $path ]->url = blob_mimes_get_info( 'PluginURI' );
	$option->response[ $path ]->slug = 'blob-mimes';
	$option->response[ $path ]->plugin = $path;
	$option->response[ $path ]->package = blob_mimes_get_remote_info( 'DownloadURI' );
	$option->response[ $path ]->new_version = $remote;
	$option->response[ $path ]->id = 0;

	// Done.
	return $option;
}
add_filter( 'transient_update_plugins', 'blob_mimes_check_update' );
add_filter( 'site_transient_update_plugins', 'blob_mimes_check_update' );
