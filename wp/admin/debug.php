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

use \blobfolio\wp\bm\mime;
use \blobfolio\wp\bm\admin;
use \blobfolio\wp\bm\debug;

if (!current_user_can('manage_options')) {
	wp_die(__('You do not have sufficient permissions to access this page.'));
}

if (!extension_loaded('fileinfo') || !defined('FILEINFO_MIME_TYPE')) {
	echo '<div class="error fade"><p>The `fileinfo` PHP extension is not installed. Detailed file information is not available.</p></div>';
	return true;
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
		}
		// All the hard work is handled by the class.
		else {
			$debug = new debug($_FILES['file']['tmp_name'], $_FILES['file']['name']);
			$results = $debug->get_results();

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
}
elseif (isset($_POST['n'])) {
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

				<?php if (!is_null($results)) { ?>
					<!-- upload form -->
					<div class="postbox">
						<h3 class="hndle">Analysis</h3>
						<div class="inside">

							<p>If this file should have been accepted and wasn't, please open an issue with the following information at <a href="https://github.com/Blobfolio/blob-mimes/issues" target="_blank">Github</a> or, if you aren't comfortable posting there, email <a href="mailto:hello@blobfolio.com">hello@blobfolio.com</a> for help. Thanks!</p>

							<textarea class="blob-mimes--results" onclick="this.select()"><?php echo esc_textarea(trim($results)); ?></textarea>

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
