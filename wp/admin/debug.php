<?php
/**
 * Lord of the Files - Upload Debugger
 *
 * Let admins do an upload dry-run so errors can be
 * reported in more detail than the normal process
 * allows.
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

if (!current_user_can('manage_options')) {
	wp_die(__('You do not have sufficient permissions to access this page.'));
}

if (!extension_loaded('fileinfo') || !defined('FILEINFO_MIME_TYPE')) {
	echo '<div class="error fade"><p>The `fileinfo` PHP extension is not installed. Detailed file information is not available.</p></div>';
	return true;
}



/**
 * Format Results
 *
 * @param  array|string $results Results.
 * @param int          $indent Indentation level.
 * @return string Results.
 */
function blob_mimes_format_results($results, $indent = 0) {
	$out = array();

	if (is_array($results)) {
		foreach ($results as $k=>$v) {
			// The main reason is special.
			if ('REASON' === $k) {
				$out[] = "\n\n" . str_repeat('=', 25);
				$out[] = 'REASON';
				$out[] = str_repeat('=', 25);
				$out[] = is_array($v) && count($v) ? $v[0] : 'N/A';
				continue;
			}

			if (is_string($k)) {
				if (is_array($v) && !count($v)) {
					$v = 'N/A';
				}

				// Simple key/value pair.
				if (is_string($v)) {
					$out[] = str_repeat('    ', $indent) . "$k:" . str_repeat(' ', 12 - strlen($k)) . $v;
					continue;
				}

				// New Section.
				if (0 === $indent) {
					$out[] = "\n\n" . str_repeat('-', 25);
				}

				$out[] = str_repeat('    ', $indent) . $k . ($indent > 0 ? ':' : '');

				if (0 === $indent) {
					$out[] = str_repeat('-', 25);
				}
			}

			if (is_string($v)) {
				$out[] = str_repeat('    ', $indent) . "•$v";
			} else {
				$tmp = blob_mimes_format_results($v, $indent + 1);
				$tmp = explode("\n", $tmp);
				foreach ($tmp as $t) {
					$out[] = $t;
				}
			}
		}
	}

	return implode("\n", $out);
}



// ---------------------------------------------------------------------
// Uploading
// ---------------------------------------------------------------------
$results = null;
$errors = array();
if ((getenv('REQUEST_METHOD') === 'POST') && wp_verify_nonce($_POST['n'], 'debug-file-validation')) {

	try {
		if (isset($_FILES)) {
			$_FILES = stripslashes_deep($_FILES);
		}

		if (!isset($_FILES['file']['tmp_name']) || !file_exists($_FILES['file']['tmp_name'])) {
			$errors[] = 'No file was uploaded, or the upload was corrupt.';
		} else {
			// Start the output.
			$results = array(
				'REASON'=>array(),
				'FILE'=>array(
					'WORDPRESS'=>array(
						'name'=>'',
						'type'=>'',
						'ext'=>'',
					),
					'FILEINFO'=>array(
						'type'=>'',
						'ext/type'=>'FALSE',
					),
					'BLOB-MIMES'=>array(
						'name'=>'',
						'type'=>'',
						'ext'=>'',
						'ext/type'=>'FALSE',
					),
				),
				'ENVIRONMENT'=>array(
					'WORDPRESS'=>array(
						'version'=>get_bloginfo('version'),
						'plugins'=>get_option('active_plugins'),
						'theme'=>basename(get_stylesheet_directory()),
					),
					'PHP'=>array(
						'version'=>phpversion(),
						'OS'=>php_uname('a'),
						'extensions'=>get_loaded_extensions(),
					),
				),
			);
			sort($results['ENVIRONMENT']['WORDPRESS']['plugins']);
			sort($results['ENVIRONMENT']['PHP']['extensions']);

			$file = $_FILES['file'];

			// First, run a simple name check.
			$results['FILE']['WORDPRESS']['name'] = $file['name'];
			$info = wp_check_filetype($file['name']);
			$results['FILE']['WORDPRESS']['ext'] = $info['ext'] ? $info['ext'] : 'FALSE';
			$results['FILE']['WORDPRESS']['type'] = $info['type'] ? $info['type'] : 'FALSE';

			// Failure by simple extension.
			if ((false === $info['ext']) || (false === $info['type'])) {
				$results['REASON'][] = "The uploaded file's name ends in an extension that is not part of the upload whitelist.";
			}

			// Now fileinfo.
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$results['FILE']['FILEINFO']['type'] = finfo_file($finfo, $file['tmp_name']);
			if (!is_string($results['FILE']['FILEINFO']['type']) || !strlen($results['FILE']['FILEINFO']['type'])) {
				$results['FILE']['FILEINFO']['type'] = 'FALSE';
			}

			if ('FALSE' !== $results['FILE']['FILEINFO']['type']) {
				$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
				$results['FILE']['FILEINFO']['ext/type'] = blobfolio\wp\bm\mime::check_alias($ext, $results['FILE']['FILEINFO']['type']) ? 'TRUE' : 'FALSE';
			}

			finfo_close($finfo);

			// Now this plugin.
			$info = blobfolio\wp\bm\mime::check_real_filetype($file['tmp_name'], $file['name']);
			$results['FILE']['BLOB-MIMES']['ext'] = $info['ext'] ? $info['ext'] : 'FALSE';
			$results['FILE']['BLOB-MIMES']['type'] = $info['type'] ? $info['type'] : 'FALSE';

			// Reconstruct the file name.
			if ((false !== $info['ext']) && (false !== $info['type'])) {
				// Is this an alias?
				$results['FILE']['BLOB-MIMES']['ext/type'] = blobfolio\wp\bm\mime::check_alias($results['FILE']['BLOB-MIMES']['ext'], $results['FILE']['BLOB-MIMES']['type']) ? 'TRUE' : 'FALSE';

				// Reconstruct the name.
				$filename_parts = explode('.', $file['name']);
				array_pop($filename_parts);
				$filename_parts[] = $results['FILE']['BLOB-MIMES']['ext'];
				$results['FILE']['BLOB-MIMES']['name'] = implode('.', $filename_parts);
			}

			// Run it through the normal upload check to see what happens.
			$info = blobfolio\wp\bm\admin::check_filetype_and_ext(null, $file['tmp_name'], $file['name'], get_allowed_mime_types());
			if (!count($results['REASON'])) {
				if ((false === $info['ext']) || (false === $info['type'])) {
					if ('FALSE' !== $results['FILE']['BLOB-MIMES']['type']) {
						if ('FALSE' !== $results['FILE']['BLOB-MIMES']['ext/type']) {
							$results['REASON'][] = 'The MIME type matches the file extension, but that extension is not whitelisted.';
						} else {
							$results['REASON'][] = 'The MIME type is not (yet) known to match the file extension.';
						}
					} elseif ('FALSE' !== $results['FILE']['FILEINFO']['type']) {
						$results['REASON'][] = 'Based on content, this file was found to not match any authorized types.';
					} else {
						$results['REASON'][] = 'Another plugin or script might be responsible for rejecting the file.';
					}
				} else {
					$results['REASON'][] = 'This file passed the primary validation checks. :) If there is a problem, it is unfortunately somewhere else.';
				}
			}

			// We're done with the original file. Try to delete
			// it just in case the request dies unexpectedly.
			@unlink($_FILES['file']['tmp_name']);
		}
	} catch (Throwable $e) {
		$errors[] = 'The file upload could not be processed.';
	} catch (Exception $e) {
		$errors[] = 'The file upload could not be processed.';
	}

	if (count($errors)) {
		foreach ($errors as $e) {
			echo '<div class="error fade"><p>' . esc_html($e) . '</p></div>';
		}
	}
} elseif (isset($_POST['n'])) {
	echo '<div class="error fade"><p>The form had expired. Please try again.</p></div>';
}

// --------------------------------------------------------------------- End uploading.
?><style type="text/css">
	.blob-mimes--container { max-width: 400px!important; }

	.blob-mimes--label,
	.blob-mimes--field,
	.blob-mimes--results,
	.blob-mimes--fieldset { display: block; }

	.blob-mimes--fieldset { margin-bottom: 20px; }
	.blob-mimes--fieldset:last-child { margin-bottom: 0; }

	.blob-mimes--label {
		font-weight: bold;
		text-transform: uppercase;
		margin-bottom: 5px;
	}

	.blob-mimes--results {
		width: 100%;
		height: 400px;
		font-size: 13px;
		font-family: 'Courier New', monospace;
		background-color: #32373C!important;
		border-color: #32373C!important;
		color: #fff!important;
	}
</style>
<div class="wrap">

	<div id="debug-log-errors"></div>

	<h2>Debug File Validation</h2>

	<p>If a file has been rejected from the Media Library for "security reasons",<br>
	use the form below to find out more information.</p>

	<p>&nbsp;</p>

	<div id="poststuff">

		<div id="post-body" class="meta-holder">
			<div class="postbox-container">

				<?php if (is_array($results)) { ?>
					<!-- upload form -->
					<div class="postbox">
						<h3 class="hndle">Analysis</h3>
						<div class="inside">

							<p>If this file should have been accepted and wasn't, please submit the following information to the official <a href="https://core.trac.wordpress.org/ticket/39963" target="_blank">WordPress issue ticket</a>. Thanks!</p>

							<textarea class="blob-mimes--results" onclick="this.select()"><?php echo esc_textarea(trim(blob_mimes_format_results($results))); ?></textarea>

						</div><!--.inside-->
					</div><!--.postbox-->
				<?php } ?>

				<!-- upload form -->
				<div class="postbox blob-mimes--container">
					<h3 class="hndle">Upload Again</h3>
					<div class="inside">

						<form method="post" action="<?php echo esc_url(admin_url('tools.php?page=blob-mimes-admin')); ?>" enctype="multipart/form-data" name="validationForm">

							<!-- nonce -->
							<input type="hidden" name="n" value="<?php echo esc_attr(wp_create_nonce('debug-file-validation')); ?>" />

							<!-- file upload -->
							<fieldset class="blob-mimes--fieldset">
								<label for="blob-mimes--file" class="blob-mimes--label">File:</label>

								<input type="file" name="file" id="blob-mimes--file" class="blob-mimes--field" required />

								<p class="description">Please re-upload the problematic file. It will <strong>not</strong> be saved to the server, but will be analyzed to provide you with information about why it failed.</p>
							</fieldset>

							<!-- submit button -->
							<fieldset class="blob-mimes--fieldset">
								<button type="submit" class="button button-large button-primary">Upload</button>
							</fieldset>

						</form>

					</div><!--.inside-->
				</div><!--.postbox-->

			</div><!--.postbox-container-->

		</div><!--#post-body-->
	</div><!--#poststuff-->

</div><!--.wrap-->
