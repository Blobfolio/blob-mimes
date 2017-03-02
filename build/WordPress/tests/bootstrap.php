<?php
/**
 * PHPUnit bootstrap file
 *
 * @package blobfolio/mimes
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the MIMEs file.
 */
require_once dirname(dirname(__FILE__)) . '/media-mimes.php';

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
