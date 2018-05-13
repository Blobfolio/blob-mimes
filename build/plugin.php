<?php
/**
 * Compile Lord of the Files Plugin
 *
 * @package blobfolio/mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

use \blobfolio\dev\plugin;

require(__DIR__ . '/lib/vendor/autoload.php');

// Set up some quick constants, namely for path awareness.
define('BOB_BUILD_DIR', __DIR__ . '/');
define('BOB_ROOT_DIR', dirname(BOB_BUILD_DIR) . '/');

// Compilation is as easy as calling this method!
plugin::compile();

// We're done!
exit(0);
