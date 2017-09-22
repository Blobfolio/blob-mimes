<?php
/**
 * Lord of the Files - Bootstrap
 *
 * There isn't much here, but we can't shove it in
 * the main index without risking blowing up ancient
 * versions of PHP. Oh well.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (!defined('ABSPATH')) {
	exit;
}

// Our autoloader.
// Run from basedir: phpab -n --tolerant -e '**/tests/**' -o ./lib/autoload.php .
require(BLOBMIMES_BASE_PATH . 'lib/autoload.php');

// Register hooks.
\blobfolio\wp\bm\admin::init();
