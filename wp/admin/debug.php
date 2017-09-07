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
	echo '<div class="error fade"><p>' . esc_html__('The `fileinfo.so` PHP extension is not installed. Detailed file information is not available.', 'blob-mimes') . '</p></div>';
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
			$errors[] = __('No file was uploaded, or the upload was corrupt.', 'blob-mimes');
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
		$errors[] = __('The file upload could not be processed.', 'blob-mimes');
	} catch (Exception $e) {
		$errors[] = __('The file upload could not be processed.', 'blob-mimes');
	}

	if (count($errors)) {
		foreach ($errors as $e) {
			echo '<div class="error fade"><p>' . esc_html($e) . '</p></div>';
		}
	}
}
elseif (isset($_POST['n'])) {
	echo '<div class="error fade"><p>' . esc_html__('The form had expired. Please try again.', 'blob-mimes') . '</p></div>';
}

// --------------------------------------------------------------------- End uploading.
?><style type="text/css">
	.hide-safe {
		position: fixed;
		top: -500px;
		left: -500px;
		height: 1px;
		width: 1px;
		overflow: hidden;
	}

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

	#blob-mimes--results {
		width: 100%;
		height: 400px;
		font-size: 13px;
		font-family: Consolas, Monaco, monospace;
		background-color: #32373C;
		border-color: #32373C;
		color: #fff;
		white-space: pre;
		overflow-y: auto;
		padding: 20px;
		box-sizing: border-box;
		transition: background .3s;
	}
	#blob-mimes--results.is-active { background-color: #000; }

	#blob-mimes--copy {
		display: block;
		margin-top: 1em;
	}

	@media (min-width: 1080px) {
		#blob-mimes--copy {
			margin-top: 0;
			position: absolute;
			bottom: 24px;
			right: 37px;
		}
	}

	.blob-mimes_blue { color: #0073AA; }
	.blob-mimes_orange { color: #D54E21; }
	.blob-mimes_red { color: #D52121; }
	.blob-mimes_green { color: #00AA1D; }
</style>
<div class="wrap">

	<div id="debug-log-errors"></div>

	<h2><?php echo esc_html__('Debug File Validation', 'blob-mimes'); ?></h2>

	<p>&nbsp;</p>

	<div id="poststuff">

		<div id="post-body" class="meta-holder">
			<div class="postbox-container">

				<?php if (!is_null($results)) { ?>
					<!-- upload form -->
					<div class="postbox">
						<h3 class="hndle"><?php echo esc_html__('Analysis', 'blob-mimes'); ?></h3>
						<div class="inside">

							<p><?php
								echo sprintf(
									esc_html__("If this file should have been accepted and wasn't, please open an issue with the following information at %s or, if you aren't comfortable posting there, email %s for help. Thanks!", 'blob-mimes'),
									'<a href="https://github.com/Blobfolio/blob-mimes/issues" target="_blank">Github</a>',
									'<a href="mailto:hello@blobfolio.com">hello@blobfolio.com</a>'
								);
								?></p>

							<div class="blob-mimes--results" id="blob-mimes--results" contenteditable><?php echo esc_html(trim($results)); ?></div>

							<button type="button" id="blob-mimes--copy" class="button button-large button-primary"><?php echo esc_html__('Copy', 'blob-mimes'); ?></button>

						</div><!--.inside-->
					</div><!--.postbox-->
				<?php } ?>

				<!-- upload form -->
				<div class="postbox blob-mimes--container">
					<h3 class="hndle"><?php echo esc_html__('Test Upload', 'blob-mimes'); ?></h3>
					<div class="inside">

						<form method="post" action="<?php echo esc_url(admin_url('tools.php?page=blob-mimes-admin')); ?>" enctype="multipart/form-data" name="validationForm">

							<!-- nonce -->
							<input type="hidden" name="n" value="<?php echo esc_attr(wp_create_nonce('debug-file-validation')); ?>" />

							<!-- file upload -->
							<fieldset class="blob-mimes--fieldset">
								<label for="blob-mimes--file" class="blob-mimes--label"><?php echo esc_html__('File', 'blob-mimes'); ?>:</label>

								<input type="file" name="file" id="blob-mimes--file" class="blob-mimes--field" required />

								<p class="description"><?php echo esc_html__('Please re-upload the problematic file. It will not be saved to the server, but will be analyzed to provide you with information about why it failed.', 'blob-mimes'); ?></p>
							</fieldset>

							<!-- submit button -->
							<fieldset class="blob-mimes--fieldset">
								<button type="submit" class="button button-large button-primary"><?php echo esc_html__('Upload', 'blob-mimes'); ?></button>
							</fieldset>

						</form>

					</div><!--.inside-->
				</div><!--.postbox-->

			</div><!--.postbox-container-->

		</div><!--#post-body-->
	</div><!--#poststuff-->

</div><!--.wrap-->

<?php if (!is_null($results)) { ?>
<script>
(function($){
	var results = $('#blob-mimes--results'),
		content = results.html(),
		colorKeys = {
			red: [
				'<?php echo esc_js(__('[error]', 'blob-mimes')); ?>'
			],
			green: [
				'<?php echo esc_js(__('[pass]', 'blob-mimes')); ?>'
			],
			blue: [
				'<?php echo str_replace(
					array('\\', "\n"),
					array('\\\\', "','"),
					trim(debug::ASCII_VALIDATION)
				); ?>',
				'<?php echo str_replace(
					array('\\', "\n"),
					array('\\\\', "','"),
					trim(debug::ASCII_SYSTEM)
				); ?>',
			],
		},
		safeReplace = function(haystack, needle, replacement) {
			return haystack.replace(
				new RegExp(needle.replace(/[.^$*+?()[{\|]/g, '\\$&'), 'g'),
				replacement
			);
		},
		i;

	// Colorize!
	for (var color of ['red','green']) {
		for (i=0; i<colorKeys[color].length; i++) {
			content = safeReplace(content, colorKeys[color][i], '<strong class="blob-mimes_' + color + '">' + colorKeys[color][i] + '</strong>');
		}
	}

	// Blue is a little special.
	for (i=0; i<colorKeys.blue.length; i++) {
		content = content.replace(colorKeys.blue[i], '<strong class="blob-mimes_blue">' + colorKeys.blue[i] + '</strong>');
	}

	// And lastly our inner-headers.
	content = content.replace(
		/(\+\-+\+)/g,
		'<strong>$1</strong>'
	);
	content = content.replace(
		/(\|\s\s[A-Z0-9\s\.\(\)\-]+\s\s\|)/g,
		'<strong>$1</strong>'
	);

	results.html(content);

	$('#blob-mimes--copy').click(function(e){
		e.preventDefault;

		var btnNormal = '<?php echo esc_js(__('Copy', 'blob-mimes')); ?>',
			btnActive = '<?php echo esc_js(__('Copied!', 'blob-mimes')); ?>',
			clippy = document.createElement('textarea'),
			content = results.text();

		// Replace our ASCII delights with plain text. Unfortunately
		// they aren't super-portable.
		content = content.replace(
			'<?php echo str_replace(
				array('\\', "\n"),
				array('\\\\', '\n'),
				trim(debug::ASCII_VALIDATION)
			); ?>',
			'##' + "\n" + '# VALIDATION' + "\n" + '##'
		);
		content = content.replace(
			' <?php echo str_replace(
				array('\\', "\n"),
				array('\\\\', '\n'),
				trim(debug::ASCII_SYSTEM)
			); ?>',
			'##' + "\n" + '# SYSTEM' + "\n" + '##'
		);

		// Wrap this in backticks to help Github formatting, in case
		// that's where they go.
		content = '```' + "\n" + content + "\n" + '```';

		// Copy to clipboard.
		clippy.value = content;
		clippy.classList.add('hide-safe');
		document.body.appendChild(clippy);
		clippy.select();
		document.execCommand('copy');
		document.body.removeChild(clippy);

		// Add some flash so people know it worked.
		results.addClass('is-active');
		$('#blob-mimes--copy').text(btnActive);
		setTimeout(function(){
			results.removeClass('is-active');
			$('#blob-mimes--copy').text(btnNormal);
		}, 500);
	});
})(jQuery);
</script>
<?php } ?>
