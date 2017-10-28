<?php
/**
 * Lord of the Files - Uninstall
 *
 * Parting is such sweet sorrow.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// Don't leave any settings behind.
delete_option('bm_contributor_notice');
