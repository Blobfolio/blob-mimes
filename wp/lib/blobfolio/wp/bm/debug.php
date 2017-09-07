<?php
/**
 * Lord of the Files - Debug tool.
 *
 * Gather data on a file upload and environment.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

class debug {

	protected $errors = array();
	protected $tests = array();
	protected $file;
	protected $filename;

	protected static $wordpress;
	protected static $php;
	protected static $mimes;

	const LINE_LENGTH = 70;

	const ASCII_VALIDATION = '
__   __ _    _     ___  ___    _  _____  ___  ___   _  _
\ \ / //_\  | |   |_ _||   \  /_\|_   _||_ _|/ _ \ | \| |
 \ V // _ \ | |__  | | | |) |/ _ \ | |   | || (_) || .` |
  \_//_/ \_\|____||___||___//_/ \_\|_|  |___|\___/ |_|\_|';
	const ASCII_SYSTEM = '
 ___ __   __ ___  _____  ___  __  __
/ __|\ \ / // __||_   _|| __||  \/  |
\__ \ \ V / \__ \  | |  | _| | |\/| |
|___/  |_|  |___/  |_|  |___||_|  |_|';


	// ---------------------------------------------------------------------
	// Init
	// ---------------------------------------------------------------------

	/**
	 * Load and test a file!
	 *
	 * @param string $file File path.
	 * @param string $filename File name.
	 * @return bool True/false.
	 */
	public function __construct($file, $filename=null) {
		$this->errors = array();
		$this->tests = array();

		// Make sure the file is readable.
		try {
			if (!is_string($file) || !$file || !@is_file($file)) {
				$this->errors[] = __('The file could not be read.', 'blob-mimes');
				return false;
			}
		} catch (\Throwable $e) {
			$this->errors[] = __('The file could not be read.', 'blob-mimes');
			return false;
		} catch (\Exception $e) {
			$this->errors[] = __('The file could not be read.', 'blob-mimes');
			return false;
		}

		// Make sure we have a name.
		if (!is_string($filename) || !$filename) {
			$filename = basename($file);
		}

		$this->file = $file;
		$this->filename = $filename;

		// Load static data.
		static::load_mimes();
		static::load_wordpress();
		static::load_php();

		// Run tests.
		$this->test_wp_check_filetype();
		$this->test_fileinfo();
		$this->test_self();

		return !count($this->errors);
	}

	// --------------------------------------------------------------------- end init



	// ---------------------------------------------------------------------
	// Tests
	// ---------------------------------------------------------------------

	/**
	 * Test: WP Check Filetype
	 *
	 * @return bool True/false.
	 */
	protected function test_wp_check_filetype() {
		if (!$this->filename) {
			return false;
		}

		$info = wp_check_filetype($this->filename);
		$test = array(
			'pass'=>((false !== $info['ext']) && (false !== $info['type'])),
			'test'=>$info,
			'result'=>'',
		);

		if (!$test['pass']) {
			$test['result'] = __('[error]', 'blob-mimes') . ' ' . __('WordPress will not process this file based on its name.', 'blob-mimes');
			$this->errors[] = __('WordPress will not process this file based on its name.', 'blob-mimes');
		}
		else {
			$test['result'] = __('[pass]', 'blob-mimes') . ' ' . __('The file extension is allowed.', 'blob-mimes');
		}

		$this->tests['WORDPRESS'] = $test;
		return $test['pass'];
	}

	/**
	 * Test: fileinfo
	 *
	 * @return bool True/false.
	 */
	protected function test_fileinfo() {
		if (!$this->file) {
			return false;
		}

		$test = array(
			'pass'=>false,
			'test'=>array(
				'type'=>false,
				'match_alias'=>false,
				'allowed_explicit'=>false,
				'allowed_alias'=>false,
			),
			'result'=>'',
		);

		// Test if we can.
		if (static::has_fileinfo()) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$test['test']['type'] = finfo_file($finfo, $this->file);

			// Couldn't get any info.
			if (!is_string($test['test']['type']) || !$test['test']['type']) {
				$test['test']['type'] = false;
				$test['result'] = __('[warning]', 'blob-mimes') . ' ' . __('The type could not be determined.', 'blob-mimes');
			}
			// Look deeper.
			else {
				$test['test']['type'] = strtolower(sanitize_mime_type($test['test']['type']));
				$ext = pathinfo($this->filename, PATHINFO_EXTENSION);

				$test['test']['match_alias'] = mime::check_alias($ext, $test['test']['type']);
				$test['test']['allowed_explicit'] = $test['test']['allowed_alias'] = in_array($test['test']['type'], static::$mimes, true);
				if (false === $test['test']['allowed_alias']) {
					$tmp = mime::check_real_filetype($this->file, $this->filename, static::$mimes);
					$test['test']['allowed_alias'] = !!$tmp['ext'];
				}

				if ($test['test']['match_alias'] && $test['test']['allowed_explicit']) {
					$test['pass'] = true;
					$test['result'] = __('[pass]', 'blob-mimes') . ' ' . __('The file type and extension are both allowed.', 'blob-mimes');
				}
				elseif ($test['test']['match_alias'] && $test['test']['allowed_alias']) {
					$test['pass'] = true;
					$test['result'] = __('[warning]', 'blob-mimes') . ' ' . __('The file type is not explicitly whitelisted, but is an alias of a whitelisted type.', 'blob-mimes');
				}
				elseif ($test['test']['allowed_alias']) {
					$test['pass'] = true;
					$test['result'] = __('[warning]', 'blob-mimes') . ' ' . __('The file extension does not match the type, however the type is allowed.', 'blob-mimes');
				}
				else {
					$test['result'] = __('[error]', 'blob-mimes') . ' ' . __('The file type is not allowed.', 'blob-mimes');
					$this->errors[] = __('The file type is not allowed.', 'blob-mimes');
				}
			}

			finfo_close($finfo);
		}
		// Skip it.
		else {
			$test['result'] = __('[skipped]', 'blob-mimes') . ' ' . __('The `fileinfo.so` PHP extension is not installed. Detailed file information is not available.', 'blob-mimes');
		}

		$this->tests['FILEINFO'] = $test;
		return $test['pass'];
	}

	/**
	 * Test: blob-mimes
	 *
	 * @return bool True/false.
	 */
	protected function test_self() {
		if (!$this->file) {
			return false;
		}

		$test = array(
			'pass'=>false,
			'test'=>array(
				'name'=>$this->filename,
				'type'=>false,
				'ext'=>false,
				'renamed'=>false,
			),
			'result'=>'',
		);

		$info = mime::check_real_filetype($this->file, $this->filename, static::$mimes);
		$test['pass'] = ((false !== $info['type']) && (false !== $info['ext']));

		if ($test['pass']) {
			$test['test']['type'] = $info['type'];
			$test['test']['ext'] = $info['ext'];

			$ext = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
			if ($ext !== $info['ext']) {
				$test['test']['renamed'] = true;
				$test['test']['name'] = mime::update_filename_extension($this->filename, $info['ext']);
				$test['result'] = __('[warning]', 'blob-mimes') . ' ' . __('The file extension does not match the type; it will be renamed.', 'blob-mimes');
			}
			else {
				$test['result'] = __('[pass]', 'blob-mimes') . ' ' . __('The file type and extension are both allowed.', 'blob-mimes');
			}
		}
		else {
			$test['result'] = __('[error]', 'blob-mimes') . ' ' . __('The file type is not allowed.', 'blob-mimes');
			$this->errors[] = __('The file type is not allowed.', 'blob-mimes');
		}

		$version = admin::get_version();
		$this->tests["BLOB-MIMES ($version)"] = $test;
		return $test['pass'];
	}


	// --------------------------------------------------------------------- end tests



	// ---------------------------------------------------------------------
	// Environment
	// ---------------------------------------------------------------------

	/**
	 * Supports Fileinfo?
	 *
	 * @return bool True/false.
	 */
	public static function has_fileinfo() {
		return (extension_loaded('fileinfo') && defined('FILEINFO_MIME_TYPE'));
	}

	/**
	 * Allowed MIME Types
	 *
	 * Statically cache the allowed MIME types
	 * to vaguely improve performance.
	 *
	 * @return bool True.
	 */
	protected static function load_mimes() {
		if (!is_array(static::$mimes)) {
			static::$mimes = get_allowed_mime_types();
		}

		return true;
	}

	/**
	 * WordPress Details
	 *
	 * Gather a list of installed plugins and themes.
	 *
	 * @return bool True.
	 */
	protected static function load_wordpress() {
		if (is_array(static::$wordpress)) {
			return true;
		}

		// Have to pull a bunch of data.
		require_once(trailingslashit(ABSPATH) . 'wp-admin/includes/plugin.php');
		$plugins_all = get_plugins();
		$plugins_active = get_option('active_plugins');
		$themes_all = wp_get_themes();
		$theme_active = basename(get_stylesheet_directory());

		static::$wordpress = array(
			'version'=>get_bloginfo('version'),
			'locale'=>get_locale(),
			'plugins'=>array(),
			'theme'=>'',
		);

		// Only include active plugins.
		if (is_array($plugins_active) && count($plugins_active)) {
			foreach ($plugins_all as $k=>$v) {
				if (in_array($k, $plugins_active, true)) {
					static::$wordpress['plugins'][] = "{$v['TextDomain']} [{$v['Version']}]";
				}
			}
			sort(static::$wordpress['plugins']);
		}

		// Only include the active theme.
		foreach ($themes_all as $k=>$v) {
			if ($k === $theme_active) {
				static::$wordpress['theme'] = "{$theme_active} [{$v['Version']}]";
				break;
			}
		}

		return true;
	}

	/**
	 * PHP Details
	 *
	 * Gather a list of PHP extensions.
	 *
	 * @return bool True.
	 */
	protected static function load_php() {
		if (!is_array(static::$php)) {
			static::$php = array(
				'version'=>phpversion(),
				'os'=>php_uname('a'),
				'extensions'=>get_loaded_extensions(),
			);
			sort(static::$php['extensions']);
		}

		return true;
	}

	// --------------------------------------------------------------------- end environment



	// ---------------------------------------------------------------------
	// Output
	// ---------------------------------------------------------------------

	/**
	 * Get Results!
	 *
	 * @param bool $collapsed Collapsed as string.
	 * @return mixed Results or false.
	 */
	public function get_results($collapsed=true) {
		if (!$this->file) {
			return false;
		}

		$results = array(
			'RESULT'=>'',
			'TESTS'=>$this->tests,
			'ENVIRONMENT'=>array(
				'WORDPRESS'=>static::$wordpress,
				'WHITELIST'=>array(
					'EXTENSIONS'=>array(),
					'MIME TYPES'=>array(),
				),
				'PHP'=>static::$php,
			),
		);

		// Populate the authorized file types.
		foreach (static::$mimes as $k=>$v) {
			$results['ENVIRONMENT']['WHITELIST']['MIME TYPES'][] = strtolower(sanitize_mime_type($v));
			$exts = explode('|', $k);
			foreach ($exts as $v2) {
				$v2 = trim(strtolower($v2));
				$v2 = ltrim($v2, '.');
				$results['ENVIRONMENT']['WHITELIST']['EXTENSIONS'][] = $v2;
			}
		}
		foreach ($results['ENVIRONMENT']['WHITELIST'] as $k=>$v) {
			$results['ENVIRONMENT']['WHITELIST'][$k] = array_unique($results['ENVIRONMENT']['WHITELIST'][$k]);
			sort($results['ENVIRONMENT']['WHITELIST'][$k]);
		}

		$this->errors = array_values(array_unique($this->errors));
		if (count($this->errors)) {
			$results['RESULT'] = __('The following error(s) were found:', 'blob-mimes') . "\n * " . implode("\n * ", $this->errors);
		}
		else {
			$results['RESULT'] = __('You *should* be able to upload this file. If not, a plugin or theme might be interfering with the process.', 'blob-mimes');
		}

		// If we aren't collapsing, we're done.
		if (!$collapsed) {
			return $results;
		}

		$out = array();
		foreach ($results as $k=>$v) {
			if ('RESULT' !== $k) {
				$out[] = '';
				$out[] = '';
			}

			// Section header.
			$out[] = $this->make_results_header($k);

			// Just print results.
			if ('RESULT' === $k) {
				$out[] = $this->make_results_value($v);
				continue;
			}

			if (!is_array($v) || !count($v)) {
				continue;
			}

			// Other Subsections.
			foreach ($v as $k2=>$v2) {
				if (!is_array($v2) || !count($v2)) {
					continue;
				}

				// Subsection header.
				$out[] = '';
				$out[] = $this->make_results_header($k2, false);

				foreach ($v2 as $k3=>$v3) {
					$key = str_replace('_', ' ', strtoupper($k3));

					// An array.
					if (is_array($v3)) {
						if (!count($v3)) {
							$out[] = $this->make_results_value('N/A', false, $key);
						}
						else {
							// Indexed array.
							$indexed = true;
							foreach (array_keys($v3) as $k4) {
								if (!is_numeric($k4)) {
									$indexed = false;
									break;
								}
							}

							// Keys don't matter, just group everything.
							if ($indexed) {
								// PHP extensions don't need separate lines.
								if (
									('EXTENSIONS' === $key) ||
									('MIME TYPES' === $key)
								) {
									$value = implode('; ', $v3);
								}
								else {
									$value = implode("\n", $v3);
								}
								$out[] = $this->make_results_value($value, true, $key);
							}
							// Break out associative arrays.
							else {
								$tmp = array();
								foreach ($v3 as $k4=>$v4) {
									$k4 = '[' . str_replace('_', ' ', strtolower($k4)) . ']';
									if (is_bool($v4)) {
										$value = $v4 ? 'TRUE' : 'FALSE';
									}
									else {
										$value = $v4;
									}
									$tmp[] = __($k4, 'blob-mimes') . " $value";
								}
								$out[] = $this->make_results_value(implode("\n", $tmp), true, $key);
							}
						}
					}
					// A string.
					else {
						$out[] = $this->make_results_value($v3, true, $key);
					}
				}
			}
		}

		return implode("\n", $out);
	}

	/**
	 * Result Header
	 *
	 * @param string $header Header.
	 * @param bool $major Major section.
	 * @return string Header.
	 */
	protected function make_results_header($header, $major=true) {
		if (!$header) {
			return '';
		}

		if ($major) {
			if ('TESTS' === $header) {
				return static::ASCII_VALIDATION;
			}
			elseif ('ENVIRONMENT' === $header) {
				return static::ASCII_SYSTEM;
			}
		}
		else {
			$header = "  $header  ";
			$length = strlen($header);
			$border = str_repeat('-', $length);
			return "+{$border}+\n|{$header}|\n+{$border}+";
		}

		return '';
	}

	/**
	 * Result Value
	 *
	 * @param mixed $value Value.
	 * @param bool $indent Indent subsequent lines.
	 * @param string $key Include the key.
	 * @return string Value.
	 */
	protected function make_results_value($value, $indent=false, $key='') {
		if (is_bool($value)) {
			$value = $value ? 'TRUE' : 'FALSE';
		}
		else {
			try {
				$value = (string) $value;
				$value = trim($value);
			} catch (\Throwable $e) {
				$value = '';
			} catch (\Exception $e) {
				$value = '';
			}
		}

		if ($key) {
			$key = __($key, 'blob-mimes');
			$value = str_pad("$key:", 15, ' ', STR_PAD_RIGHT) . $value;
		}

		// Nothing to wrap.
		if (strlen($value) <= static::LINE_LENGTH && (false === strpos($value, "\n"))) {
			return $value;
		}

		$wrap = $indent ? static::LINE_LENGTH - 15 : static::LINE_LENGTH;
		$value = wordwrap($value, $wrap);
		$value = explode("\n", $value);
		foreach ($value as $k=>$v) {
			$value[$k] = trim($value[$k]);

			if ($k > 0 && $indent) {
				$value[$k] = str_repeat(' ', 15) . $value[$k];
			}
		}

		return implode("\n", $value);
	}

	// --------------------------------------------------------------------- end output
}
