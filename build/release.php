<?php
/**
 * Package WordPress Plugin
 *
 * We want to get rid of source files and whatnot, and since they're
 * kinda all over the place, it is better to let a robot handle it.
 *
 * Dirty, dirty work.
 *
 * @package blobfolio/mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

define('BUILD_DIR', dirname(__FILE__) . '/');
define('PLUGIN_BASE', dirname(BUILD_DIR) . '/wp/');
define('RELEASE_BASE', dirname(BUILD_DIR) . '/blob-mimes/');



echo "\n";
echo "+ Copying the source.\n";

// Delete the release base if it already exists.
if (file_exists(RELEASE_BASE)) {
	shell_exec('rm -rf ' . escapeshellarg(RELEASE_BASE));
}

// Copy the trunk.
shell_exec('cp -aR ' . escapeshellarg(PLUGIN_BASE) . ' ' . escapeshellarg(RELEASE_BASE));



echo "+ Cleaning the source.\n";

// Files.
$tmp = array(
	'.travis.yml',
	'phpcs.ruleset.xml',
	'phpunit.xml.dist',
	'README.md',
);
foreach ($tmp as $v) {
	unlink(RELEASE_BASE . $v);
}

// Directories.
$tmp = array(
	'bin',
	'tests',
);
foreach ($tmp as $v) {
	shell_exec('rm -rf ' . escapeshellarg(RELEASE_BASE . $v));
}

// Miscellaneous.
shell_exec('find ' . escapeshellarg(RELEASE_BASE) . ' -name ".gitignore" -type f -delete');



echo "+ Fixing permissions.\n";
shell_exec('find ' . escapeshellarg(RELEASE_BASE) . ' -type d -print0 | xargs -0 chmod 755');
shell_exec('find ' . escapeshellarg(RELEASE_BASE) . ' -type f -print0 | xargs -0 chmod 644');



echo "\nDone!.\n";