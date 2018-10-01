<?php
/**
 * Lord of the Files - Uninstall
 *
 * Parting is such sweet sorrow.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

// phpcs:disable SlevomatCodingStandard.Namespaces

/**
 * Do not execute this file directly.
 */
if (! defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

// Don't leave any settings behind.
delete_option('bm_contributor_notice');
delete_option('bm_remote_contributors');

// Unhook the remote contributor cronjob if necessary.
$next = wp_next_scheduled('cron_get_remote_contributors');
if ($next) {
	wp_unschedule_event($next, 'cron_get_remote_contributors');
}
