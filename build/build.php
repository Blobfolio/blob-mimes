<?php
/**
 * Compile MIME source data.
 *
 * This build script will download MIME data from various sources
 * and combine the results into nice and tidy JSON files, one
 * organized by extension and one by MIME type.
 *
 * This script should be run via php-cli.
 *
 * Requires:
 * PHP 7+
 * UNIX
 * CURL
 * MBSTRING
 * SIMPLEXML
 *
 * @package blobfolio/mimes
 * @author	Blobfolio, LLC <hello@blobfolio.com>
 */

// -------------------------------------------------
// Setup/Env.

define('BUILD_PATH', dirname(__FILE__));
define('SOURCE_PATH', BUILD_PATH . '/src');

define('EXT_PATH', BUILD_PATH . '/mimes_by_extension.json');
define('MIME_PATH', BUILD_PATH . '/extensions_by_mime.json');
define('EXT_DEFAULT', array(
	'ext'=>'',
	'mime'=>array(),
	'source'=>array(),
	'alias'=>array(),
	'primary'=>''
));
define('MIME_DEFAULT', array(
	'mime'=>'',
	'ext'=>array(),
	'source'=>array(),
));
$mimes_by_extension = array();
$extensions_by_mime = array();
$aliases = array();

define('IANA_API', 'https://www.iana.org/assignments/media-types');
define('IANA_CATEGORIES', array(
	'application',
	'audio',
	'font',
	'image',
	'message',
	'model',
	'multipart',
	'text',
	'video'
));

define('APACHE_API', 'https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');

define('NGINX_API', 'http://hg.nginx.org/nginx/raw-file/default/conf/mime.types');

define('FREEDESKTOP_API', 'https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in');

$start = microtime(true);



/**
 * STDOUT wrapper.
 *
 * Make it easier to print progress to the terminal.
 *
 * @param string $line Content.
 * @param bool $dividers Print dividing lines.
 * @return void Nothing.
 */
function debug_stdout(string $line='', bool $dividers=false) {
	if ($dividers) {
		echo str_repeat('-', 50) . "\n";
	}
	echo "$line\n";
	if ($dividers) {
		echo str_repeat('-', 50) . "\n";
	}
}



/**
 * Recursive rmdir()
 *
 * Delete all files within a directory, then
 * the directory itself.
 *
 * @param string $path Path.
 * @return bool Status.
 */
function recursive_rm(string $path='') {
	if (false === $path = realpath($path)) {
		return false;
	}

	$path = rtrim($path, '/');

	// This must be below the build directory, and not this script.
	if (
		mb_substr($path, 0, mb_strlen(BUILD_PATH) + 1) !== BUILD_PATH . '/' ||
		BUILD_PATH === $path ||
		__FILE__ === $path
	) {
		return false;
	}

	// Files are easy.
	if (is_file($path)) {
		@unlink($path);
	}
	// Directories require recursion.
	elseif ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if (in_array($file, array('.', '..'), true)) {
				continue;
			}

			recursive_rm("$path/$file");
		}
		@rmdir($path);
	}

	return true;
}



/**
 * Batch CURL URLs
 *
 * It is much more efficient to use multi-proc
 * CURL as there are hundreds of files to get.
 *
 * @param array $urls URLs.
 * @return array Responses.
 */
function fetch_urls(array $urls=array()) {
	$fetched = array();

	// Bad start...
	if (!count($urls)) {
		return $fetched;
	}

	// Loosely filter URLs.
	foreach ($urls as $k=>$v) {
		$urls[$k] = filter_var($v, FILTER_SANITIZE_URL);
		if (!preg_match('/^https?:\/\//', $urls[$k])) {
			unset($urls[$k]);
		}
	}

	$urls = array_chunk($urls, 25);

	foreach ($urls as $chunk) {
		$multi = curl_multi_init();
		$curls = array();

		// Set up curl request for each site.
		foreach ($chunk as $url) {
			$curls[$url] = curl_init($url);

			curl_setopt($curls[$url], CURLOPT_HEADER, false);
			curl_setopt($curls[$url], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curls[$url], CURLOPT_TIMEOUT, 10);
			curl_setopt($curls[$url], CURLOPT_USERAGENT, 'blob-mimes');
			curl_setopt($curls[$url], CURLOPT_FOLLOWLOCATION, true);

			curl_multi_add_handle($multi, $curls[$url]);
		}

		// Process requests.
		do {
			curl_multi_exec($multi, $running);
			curl_multi_select($multi);
		} while ($running > 0);

		// Update information.
		foreach ($chunk as $url) {
			$fetched[$url] = (int) curl_getinfo($curls[$url], CURLINFO_HTTP_CODE);
			if ($fetched[$url] >= 200 && $fetched[$url] < 400) {
				$fetched[$url] = curl_multi_getcontent($curls[$url]);
			}
			curl_multi_remove_handle($multi, $curls[$url]);
		}

		curl_multi_close($multi);
	}

	return $fetched;
}



/**
 * Explode a string by line.
 *
 * @param string $str String.
 * @return array Lines.
 */
function explode_lines(string $str='') {
	$str = str_replace("\r\n", "\n", $str);
	$str = preg_replace('/\v/u', "\n", $str);
	$str = explode("\n", $str);
	$str = array_map('trim', $str);
	return array_filter($str, 'strlen');
}



/**
 * Record MIME/Ext Data
 *
 * Redundant and overlapping data can be recovered from
 * multiple sources. This keeps track of all of that in
 * one place, and also sanitizes data, etc.
 *
 * @param string $mime MIME type.
 * @param string $ext File extension.
 * @param string $source Data source.
 * @return bool Status.
 */
function save_mime_ext_pair(string $mime='', string $ext='', string $source='') {
	global $mimes_by_extension;
	global $extensions_by_mime;
	global $aliases;

	$ext = mb_strtolower($ext, 'UTF-8');
	$mime = mb_strtolower($mime, 'UTF-8');
	$mime = preg_replace('/[^-+*.a-zA-Z0-9\/]/', '', $mime);

	if (!mb_strlen($ext) || !mb_strlen($mime) || !mb_strlen($source)) {
		return false;
	}

	// Mimes by extension.
	if (!isset($mimes_by_extension[$ext])) {
		$mimes_by_extension[$ext] = EXT_DEFAULT;
		$mimes_by_extension[$ext]['ext'] = $ext;
	}

	if (!in_array($mime, $mimes_by_extension[$ext]['mime'], true)) {
		$mimes_by_extension[$ext]['mime'][] = $mime;
	}

	if (in_array($mime, $aliases, true) && !in_array($mime, $mimes_by_extension[$ext]['alias'], true)) {
		$mimes_by_extension[$ext]['alias'][] = $mime;
	}

	if (!in_array($source, $mimes_by_extension[$ext]['source'], true)) {
		$mimes_by_extension[$ext]['source'][] = $source;
	}

	// Extensions by mime.
	if (!isset($extensions_by_mime[$mime])) {
		$extensions_by_mime[$mime] = MIME_DEFAULT;
		$extensions_by_mime[$mime]['mime'] = $mime;
	}

	if (!in_array($ext, $extensions_by_mime[$mime]['ext'], true)) {
		$extensions_by_mime[$mime]['ext'][] = $ext;
	}

	if (!in_array($source, $extensions_by_mime[$mime]['source'], true)) {
		$extensions_by_mime[$mime]['source'][] = $source;
	}

	return true;
}



// -------------------------------------------------
// Begin!

if (file_exists(SOURCE_PATH)) {
	recursive_rm(SOURCE_PATH);
}
mkdir(SOURCE_PATH, 0755);

if (file_exists(EXT_PATH)) {
	recursive_rm(EXT_PATH);
}

if (file_exists(MIME_PATH)) {
	recursive_rm(MIME_PATH);
}



// -------------------------------------------------
// IANA

debug_stdout('IANA', true);

// Get categories.
$urls = array();
debug_stdout('   ++ Fetching MIME lists...');
foreach (IANA_CATEGORIES as $category) {
	$urls[] = IANA_API . "/$category.csv";
}

$data = fetch_urls($urls);
$urls = array();
mkdir(SOURCE_PATH . '/iana/categories', 0755, true);
foreach ($data as $k=>$v) {
	if (is_int($v)) {
		debug_stdout('      ++ ERROR retrieving ' . basename($k));
		continue;
	}

	debug_stdout('      ++ Parsing ' . basename($k) . '...');

	// Save file.
	@file_put_contents(SOURCE_PATH . '/iana/categories/' . basename($k), $v);

	// Parse CSV.
	$v = explode_lines($v);

	foreach ($v as $raw) {
		$line = str_getcsv($raw);
		if (count($line) < 2 || !mb_strlen($line[1])) {
			continue;
		}

		// 0 NAME.
		// 1 TEMPLATE.

		if (preg_match('/(deprecated|obsolete)/i', $line[0])) {
			$aliases[] = $line[1];
		}

		$urls[] = IANA_API . "/{$line[1]}";
	}
}

// Get templates to parse extensions.
debug_stdout('   ++ Fetching templates...');
$data = fetch_urls($urls);
mkdir(SOURCE_PATH . '/iana/templates', 0755);
foreach ($data as $k=>$v) {
	if (is_int($v)) {
		continue;
	}

	// Save file.
	$mime = mb_strtolower(mb_substr($k, mb_strlen(IANA_API) + 1));
	$dirname = SOURCE_PATH . '/iana/templates/' . dirname($mime);

	if (!file_exists($dirname)) {
		mkdir($dirname, 0755, true);
	}
	@file_put_contents("$dirname/" . basename($mime) . '.txt', $v);

	// Are there extensions?
	preg_match_all('/\s*File extension\(s\):([\.,\da-z\s\-_]+)/i', $v, $matches);
	if (count($matches[1])) {
		$raw = explode(',', $matches[1][0]);
		$raw = array_map('trim', $raw);
		$exts = array();
		foreach ($raw as $ext) {
			$ext = rtrim(ltrim(mb_strtolower($ext), '.*'), '.*');
			if (
				preg_match('/^[\da-z]+[\da-z\-_]*[\da-z]+$/', $ext) &&
				'none' !== $ext
			) {
				$exts[] = $ext;
			}
		}
		if (count($exts)) {
			foreach ($exts as $ext) {
				save_mime_ext_pair($mime, $ext, 'IANA');
			}
		}
	}
}



// -------------------------------------------------
// Apache

debug_stdout('');
debug_stdout('Apache', true);
debug_stdout('   ++ Fetching MIME list...');
$data = fetch_urls(array(APACHE_API));
if (!is_int($data[APACHE_API])) {
	// Save it.
	@file_put_contents(SOURCE_PATH . '/apache.txt', $data[APACHE_API]);

	// Parse output... much simpler than IANA.
	$lines = explode_lines($data[APACHE_API]);
	foreach ($lines as $line) {
		if (mb_substr($line, 0, 1) === '#') {
			continue;
		}
		$line = preg_replace('/\s+/u', ' ', $line);
		$line = explode(' ', $line);
		if (count($line) < 2) {
			continue;
		}

		$mime = $line[0];
		unset($line[0]);
		foreach ($line as $ext) {
			save_mime_ext_pair($mime, $ext, 'Apache');
		}
	}
}



// -------------------------------------------------
// Nginx

debug_stdout('');
debug_stdout('Nginx', true);
debug_stdout('   ++ Fetching MIME list...');
$data = fetch_urls(array(NGINX_API));
if (!is_int($data[NGINX_API])) {
	// Save it.
	@file_put_contents(SOURCE_PATH . '/nginx.txt', $data[NGINX_API]);

	// Parse output... much simpler than IANA.
	$lines = explode_lines($data[NGINX_API]);
	foreach ($lines as $line) {
		if (mb_substr($line, 0, 1) === '#' || false !== mb_strpos($line, '{') || false !== mb_strpos($line, '}')) {
			continue;
		}
		$line = rtrim($line, ';');
		$line = preg_replace('/\s+/u', ' ', $line);
		$line = explode(' ', $line);
		if (count($line) < 2) {
			continue;
		}

		$mime = $line[0];
		unset($line[0]);
		foreach ($line as $ext) {
			save_mime_ext_pair($mime, $ext, 'Nginx');
		}
	}
}



// -------------------------------------------------
// Free Desktop

debug_stdout('');
debug_stdout('freedesktop.org', true);
debug_stdout('   ++ Fetching MIME list...');
$data = fetch_urls(array(FREEDESKTOP_API));
if (!is_int($data[FREEDESKTOP_API])) {
	// Save it.
	@file_put_contents(SOURCE_PATH . '/freedesktop.xml', $data[FREEDESKTOP_API]);

	$data = simplexml_load_string($data[FREEDESKTOP_API]);
	foreach ($data as $type) {
		// First, get the MIME(s).
		$mimes = array();
		foreach ($type->attributes() as $k=>$v) {
			if ('type' === $k) {
				$mimes[] = (string) $v;
			}
		}

		// Might also be in aliases.
		if (isset($type->alias)) {
			foreach ($type->alias as $alias) {
				foreach ($alias->attributes() as $k=>$v) {
					if ('type' === $k) {
						$mimes[] = (string) $v;
						$aliases[] = (string) $v;
					}
				}
			}
		}

		// Extensions are hidden in globs.
		$exts = array();
		if (isset($type->glob)) {
			foreach ($type->glob as $glob) {
				foreach ($glob->attributes() as $k=>$v) {
					if ('pattern' === $k) {
						$v = ltrim( (string) $v, '.*');
						if (preg_match('/^[\da-z]+[\da-z\-\_]*[\da-z]+$/', $v)) {
							$exts[] = $v;
						}
					}
				}
			}
		}

		// We have something!
		if (count($exts) && count($mimes)) {
			foreach ($exts as $ext) {
				foreach ($mimes as $mime) {
					save_mime_ext_pair($mime, $ext, 'freedesktop.org');
				}
			}
		}
	}
}



// -------------------------------------------------
// Clean Up!

debug_stdout('');
debug_stdout('Finishing Touches', true);
debug_stdout('   ++ Cleaning data...');

// Calculate primary mime.
foreach ($mimes_by_extension as $k=>$v) {
	$possible = array_diff($v['mime'], $v['alias']);
	if (!count($possible)) {
		$possible = $v['mime'];
	}
	sort($possible);

	$mimes_by_extension[$k]['primary'] = $possible[0];
	$primaries[$k] = $possible[0];
}

debug_stdout('   ++ Sorting data...');
ksort($mimes_by_extension);
ksort($extensions_by_mime);

// Save data!
debug_stdout('   ++ Saving data...');
@file_put_contents(EXT_PATH, json_encode($mimes_by_extension));
@file_put_contents(MIME_PATH, json_encode($extensions_by_mime));

// Lastly, generate WordPress File
debug_stdout('   ++ Saving WP media-mimes.php...');
$wp_data = array();
foreach($mimes_by_extension as $k=>$v){
	$v['mime'] = (array) $v['mime'];
	$wp_data[$k] = array_merge($v['mime'], $v['alias']);
	$wp_data[$k] = array_unique($wp_data[$k]);
	sort($wp_data[$k]);
}
$wp_data_out = array();
foreach($wp_data as $k=>$v){
	$wp_data_out[] = "\t\t'$k'=>array(";
	foreach($v as $v2){
		$wp_data_out[] = "\t\t\t'$v2',";
	}
	$wp_data_out[] = "\t\t),";
}
$wp_data_out = implode("\n", $wp_data_out);
$wp_file = @file_get_contents(BUILD_PATH . '/WordPress/media-mimes.template');
$wp_file = str_replace('%MIMES_BY_EXTENSION%', $wp_data_out, $wp_file);
@file_put_contents(BUILD_PATH . '/WordPress/media-mimes.php', $wp_file);


$end = microtime(true);
debug_stdout('');
debug_stdout('Done!', true);
debug_stdout('   ++ Found ' . count($mimes_by_extension) . ' extensions, ' . count($extensions_by_mime) . ' MIME types.');
debug_stdout('   ++ Finished in ' . round($end - $start, 2) . ' seconds.');

