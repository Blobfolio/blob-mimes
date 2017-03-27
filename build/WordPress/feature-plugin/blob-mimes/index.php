<?php
// @codingStandardsIgnoreFile
/**
 * MIME Alias Handling
 *
 * @package blob-mimes
 * @version 0.1.1
 *
 * @see {https://core.trac.wordpress.org/ticket/39963}
 * @see {https://core.trac.wordpress.org/ticket/40175}
 * @see {https://github.com/Blobfolio/blob-mimes/tree/master/build/WordPress}
 *
 * @wordpress-plugin
 * Plugin Name: MIME Alias Handling
 * Plugin URI: https://github.com/Blobfolio/blob-mimes
 * Description: Feature Plugin integrating MIME alias support into the file upload validation process.
 * Version: 0.1.1
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
 * De-activate the plugin if the functions or files
 * have already been implemented in the core.
 *
 * @since 0.1.0
 *
 * @return void Nothing.
 */
function blob_mimes_deactivate() {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	deactivate_plugins( dirname( __FILE__ ) . '/index.php' );
}



/**
 * Override File Validation
 *
 * In plugin form this has to be executed via
 * filter, but an actual merger would instead
 * see a rewrite of wp_check_filetype_and_ext()
 *
 * @since 0.1.0
 *
 * @see wp_check_filetype_and_ext()
 *
 * @param  array  $checked Previous check status.
 * @param string $file File path.
 * @param string $filename File name.
 * @param array  $mimes Mimes.
 * @return array    Checked status.
 */
function blob_mimes_check_filetype_and_ext( $checked, $file, $filename, $mimes ) {
	// We don't care what WP has already done.
	$proper_filename = false;

	// Do basic extension validation and MIME mapping.
	$wp_filetype = wp_check_real_filetype( $file, $filename, $mimes );
	$ext = $wp_filetype['ext'];
	$type = $wp_filetype['type'];

	// We can't do any further validation without a file to work with.
	if ( ! file_exists( $file ) ) {
		return compact( 'ext', 'type', 'proper_filename' );
	}

	// If the type is valid, should we be renaming the file?
	if ( false !== $ext && false !== $type ) {
		$filename_parts = explode( '.', $filename );
		array_pop( $filename_parts );
		$filename_parts[] = $ext;
		$new_filename = implode( '.', $filename_parts );
		if ( $filename !== $new_filename ) {
			$proper_filename = implode( '.', $filename_parts );
		}
	}

	return compact( 'ext', 'type', 'proper_filename' );
}
add_filter( 'wp_check_filetype_and_ext', 'blob_mimes_check_filetype_and_ext', 10, 4 );



if ( function_exists( 'wp_check_real_filetype' ) ) {
	blob_mimes_deactivate();
} else {
	/**
	 * Retrieve the "real" file type from the file.
	 *
	 * This extends `wp_check_filetype()` to additionally
	 * consider content-based indicators of a file's
	 * true type.
	 *
	 * The content-based type will override the name-based
	 * type if available and included in the $mimes list.
	 *
	 * A false response will be set if the extension is
	 * not allowed, or if a "real MIME" was found and
	 * that MIME is not allowed.
	 *
	 * @since 0.1.0
	 *
	 * @see wp_check_filetype()
	 * @see wp_check_filetype_and_ext()
	 *
	 * @param string $file Full path to the file.
	 * @param string $filename The name of the file (may differ from $file due to $file being in a tmp directory).
	 * @param array  $mimes Optional. Key is the file extension with value as the mime type.
	 * @return array Values with extension first and mime type.
	 */
	function wp_check_real_filetype( $file, $filename = null, $mimes = null ) {
		// Default filename.
		if ( empty( $filename ) ) {
			$filename = basename( $file );
		}

		// Default MIMEs.
		if ( empty( $mimes ) ) {
			$mimes = get_allowed_mime_types();
		}

		// Run a name-based check first.
		$checked = wp_check_filetype( $filename, $mimes );

		// Only dig deeper if we can.
		if (
			false !== $checked['ext'] &&
			false !== $checked['type'] &&
			file_exists( $file )
		) {
			$real_mime = false;

			try {
				// Try exif first. It is commonly applicable and
				// relatively low in overhead.
				// TODO requires patch #40017.
				// $real_mime = wp_get_image_mime( $file );
				// Fall back to fileinfo, if available.
				if (
					false === $real_mime &&
					extension_loaded( 'fileinfo' ) &&
					defined( 'FILEINFO_MIME_TYPE' )
				) {
					$finfo = finfo_open( FILEINFO_MIME_TYPE );
					$real_mime = finfo_file( $finfo, $file );
					finfo_close( $finfo );

					// Account for inconsistent return values.
					if ( ! is_string( $real_mime ) || ! strlen( $real_mime ) ) {
						$real_mime = false;
					}
				}
			} catch ( Throwable $e ) {
				$real_mime = false;
			} catch ( Exception $e ) {
				$real_mime = false;
			}

			// Evaluate our real MIME.
			if ( false !== $real_mime ) {
				$real_mime = strtolower( sanitize_mime_type( $real_mime ) );
				if ( ! wp_check_mime_alias( $checked['ext'], $real_mime ) ) {
					// If the extension is incorrect but the type is otherwise
					// valid, update the extension.
					if ( false !== $extensions = array_search( $real_mime, $mimes, true ) ) {
						$extensions = explode( '|', $extensions );
						$checked['ext'] = $extensions[0];
						$checked['type'] = $real_mime;
					} // Otherwise reject the results.
					else {
						$checked['ext'] = false;
						$checked['type'] = false;
					}
				}
			}
		}// End content-based type checking.

		/**
		 * Filters the real check.
		 *
		 * @since 0.1.0
		 *
		 * @param array Found values with extension first and mime type.
		 * @param string $file Full path to the file.
		 * @param string $filename The name of the file (may differ from $file due to $file being in a tmp directory).
		 * @param array $mimes Optional. Key is the file extension with value as the mime type.
		 */
		return apply_filters( 'wp_check_real_filetype', $checked, $file, $filename, $mimes );
	}
}



if ( function_exists( 'wp_check_mime_alias' ) ) {
	blob_mimes_deactivate();
} else {
	/**
	 * Check extension and MIME pairing.
	 *
	 * @since 0.1.0
	 *
	 * @param string $ext File extension.
	 * @param string $mime MIME type.
	 * @return bool True/false.
	 */
	function wp_check_mime_alias( $ext = '', $mime = '' ) {
		// Load MIME aliases.
		require_once( dirname( __FILE__ ) . '/media-mimes.php' );

		// Standardize inputs.
		$mime = strtolower( sanitize_mime_type( $mime ) );
		$ext = trim( strtolower( $ext ) );
		$ext = ltrim( $ext, '.' );

		// Can't continue if the extension is not in the database.
		if ( false === ( $mimes = wp_get_mime_aliases( $ext ) ) ) {
			/**
			 * Filters the extension/MIME check.
			 *
			 * @since 0.1.0
			 *
			 * @param bool $match The result: True or false.
			 * @param string $ext The file extension.
			 * @param string $mime The MIME type.
			 */
			return apply_filters( 'wp_check_mime_alias', false, $ext, $mime );
		}

		// Before looking for matches, convert any generic CDFV2
		// types into an equally generic, but less variable type.
		if ( 0 === strpos( $mime, 'application/cdfv2' ) ) {
			$mime = 'application/vnd.ms-office';
		}

		if ( 'application/octet-stream' === $mime ) {
			/**
			 * Require Checks For application/octet-stream
			 *
			 * While application/octet-stream a valid media
			 * type, it is also fileinfo's version of a shrug.
			 * In general, it is not authoritative enough a
			 * result from which to reject a file.
			 *
			 * @since 0.1.0
			 *
			 * @param bool $enforce_checking Run checks.
			 */
			$soft_pass = apply_filters( 'wp_check_application_octet_stream', false );
			if ( false === $soft_pass ) {
				return apply_filters( 'wp_check_mime_alias', true, $ext, $mime );
			}
		}

		// Test for the literal MIME, but also certain generic
		// variations like x-subtype and vnd.subtype.
		$test = array( $mime );

		$parts = explode( '/', $mime );
		$subtype = count( $parts ) - 1;
		if ( 'x-' === substr( $parts[ $subtype ], 0, 2 ) ) {
			$parts[ $subtype ] = substr( $parts[ $subtype ], 2 );
		} else {
			$parts[ $subtype ] = 'x-' . $parts[ $subtype ];
		}
		$test[] = implode( '/', $parts );

		$parts = explode( '/', $mime );
		$subtype = count( $parts ) - 1;
		if ( 'vnd.' === substr( $parts[ $subtype ], 0, 4 ) ) {
			$parts[ $subtype ] = substr( $parts[ $subtype ], 4 );
		} else {
			$parts[ $subtype ] = 'vnd.' . $parts[ $subtype ];
		}
		$test[] = implode( '/', $parts );

		// Overlap is success!
		$found = array_intersect( $test, $mimes );
		$match = count( $found ) > 0;

		return apply_filters( 'wp_check_mime_alias', $match, $ext, $mime );
	}
}



// Update handler.
@require_once( dirname( __FILE__ ) . '/functions-updates.php' );
