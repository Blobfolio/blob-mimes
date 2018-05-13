<?php
/**
 * Compile Lord of the Files Plugin
 *
 * @package blobfolio/mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */
namespace blobfolio\dev;

class plugin extends \blobfolio\bob\base\build_wp {
	const NAME = 'blob-mimes';

	// Various file paths.
	const SOURCE_DIR = BOB_ROOT_DIR . 'wp/';
	const PHPAB_AUTOLOADER = BOB_ROOT_DIR . 'wp/lib/autoload.php';
	const SHITLIST = array(
		'#/bin/#',
		'#/tests/#',
	);

	const BINARIES = array('phpab');

	// Release info.
	const RELEASE_TYPE = 'copy';
	const RELEASE_OUT = BOB_ROOT_DIR . '/blob-mimes/';

	// There are no file dependencies.
	const SKIP_FILE_DEPENDENCIES = true;
}
