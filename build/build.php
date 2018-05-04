<?php
/**
 * Compile MIME source data.
 *
 * This build script will download MIME data from various sources and
 * combine the results into nice and tidy JSON files, one organized by
 * extension and one by MIME type.
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
 *
 * @see {https://github.com/Blobfolio/blob-mimes}
 */

/**
 * Data Source: IANA
 *
 * @see {https://www.iana.org/assignments/media-types}
 *
 * @copyright 2017 IETF Trust
 * @license https://www.rfc-editor.org/copyright/ rfc-copyright-story
 */

/**
 * Data Source: Apache
 *
 * @see {https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types}
 *
 * @copyright 2017 The Apache Software Foundation
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache
 */

/**
 * Data Source: Nginx
 *
 * @see {http://hg.nginx.org/nginx/raw-file/default/conf/mime.types}
 *
 * @copyright 2017 NGINX Inc.
 * @license https://opensource.org/licenses/BSD-2-Clause BSD
 */

/**
 * Data Source: Freedesktop.org
 *
 * @see {https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in}
 *
 * @copyright 2017 Freedesktop.org
 * @license https://opensource.org/licenses/MIT MIT
 */

/**
 * Data Source: Apache Tika
 *
 * @see {https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml}
 *
 * @copyright 2017 The Apache Software Foundation
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache
 */



// -------------------------------------------------
// Setup/Env.

// Load the bootstrap.
@require_once(dirname(dirname(__FILE__)) . '/lib/vendor/autoload.php');

define('BUILD_PATH', dirname(__FILE__));
define('SOURCE_PATH', BUILD_PATH . '/src');

// How long should downloaded files be cached?
define('DOWNLOAD_CACHE', 7200);

define('EXT_PATH', BUILD_PATH . '/mimes_by_extension.json');
define('MIME_PATH', BUILD_PATH . '/extensions_by_mime.json');
define('DATA_PATH', dirname(BUILD_PATH) . '/lib/blobfolio/mimes/data.php');
define('DATA_SRC', BUILD_PATH . '/skel/data.template');
define('WP_SRC', BUILD_PATH . '/skel/wp.template');
define('WP_PATH', dirname(BUILD_PATH) . '/wp/lib/blobfolio/wp/bm/mime/aliases.php');

define('EXT_DEFAULT', array(
	'ext'=>'',
	'mime'=>array(),
	'source'=>array(),
	'alias'=>array(),
	'primary'=>'',
));
define('MIME_DEFAULT', array(
	'mime'=>'',
	'ext'=>array(),
	'source'=>array(),
));
$mimes_by_extension = array();
$extensions_by_mime = array();
$consensus_ext = array();

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
	'video',
));

define('APACHE_API', 'https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types');

define('NGINX_API', 'http://hg.nginx.org/nginx/raw-file/default/conf/mime.types');

define('FREEDESKTOP_API', 'https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in');

define('TIKA_API', 'https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml');

// Manual fixes (by MIME) for hardcoded problems.
define('MAGIC_LIST_BY_MIME', array(
	'application/vnd.ms-word'=>array(
		'application/vnd.ms-office',
		'application/xml',
	),
	'application/vnd.ms-excel'=>array(
		'application/vnd.ms-office',
		'application/xml',
	),
	'application/vnd.ms-powerpoint'=>array(
		'application/vnd.ms-office',
	),
	'application/vnd.openxmlformats-officedocument'=>array(
		'application/vnd.ms-office',
	),
	'application/vnd.ms-excel.sheet.macroenabled.12'=>array(
		'application/zip',
	),
));

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
 * URL to Cache Path
 *
 * The local name to use for a given URL.
 *
 * @param string $url URL.
 * @return string Path.
 */
function cache_path(string $url) {
	// Strip and translate a little.
	$url = strtolower($url);
	$url = preg_replace('/^https?:\/\//', '', $url);
	$url = str_replace(array('/', '\\', '?', '#'), '-', $url);

	return SOURCE_PATH . '/' . $url;
}



/**
 * Get Cache
 *
 * Return the local content if available.
 *
 * @param string $url URL.
 * @return string|bool Content or false.
 */
function get_cache(string $url) {
	static $limit;

	// Set the limit if we haven't already.
	if (is_null($limit)) {
		file_put_contents(SOURCE_PATH . '/limit', 'hi');
		$limit = filemtime(SOURCE_PATH . '/limit') - DOWNLOAD_CACHE;
		unlink(SOURCE_PATH . '/limit');
	}

	try {
		$file = cache_path($url);
		if (file_exists($file)) {
			if (filemtime($file) < $limit) {
				unlink($file);
			}
			else {
				return file_get_contents($file);
			}
		}
	} catch (Throwable $e) {
		return false;
	}

	return false;
}



/**
 * Save Cache
 *
 * Save a fetched URL locally.
 *
 * @param string $url URL.
 * @param string $content Content.
 * @return bool True/false.
 */
function save_cache(string $url, string $content) {
	try {
		$file = cache_path($url);
		return @file_put_contents($file, $content);
	} catch (Throwable $e) {
		return false;
	}

	return false;
}



/**
 * Batch CURL URLs
 *
 * It is much more efficient to use multi-proc CURL as there are
 * hundreds of files to get.
 *
 * @param array $urls URLs.
 * @return array Responses.
 */
function fetch_urls(array $urls=array()) {
	$fetched = array();
	$cached = array();

	// Bad start...
	if (!count($urls)) {
		return $fetched;
	}

	// Loosely filter URLs, and look for cache.
	foreach ($urls as $k=>$v) {
		$urls[$k] = filter_var($v, FILTER_SANITIZE_URL);
		if (!preg_match('/^https?:\/\//', $urls[$k])) {
			unset($urls[$k]);
			continue;
		}

		if (false !== $cache = get_cache($urls[$k])) {
			$cached[$urls[$k]] = $cache;
			unset($urls[$k]);
			continue;
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
				save_cache($url, $fetched[$url]);
			}
			curl_multi_remove_handle($multi, $curls[$url]);
		}

		curl_multi_close($multi);
	}

	// Add our cached results back.
	foreach ($cached as $k=>$v) {
		$fetched[$k] = $v;
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
 * Array to PHP Code
 *
 * Convert a variable into a string representing PHP code.
 *
 * @param array $var Data.
 * @param int $indents Number of tabs to append.
 * @return string Code.
 */
function array_to_php($var, int $indents=1) {
	if (!is_array($var) || !count($var)) {
		return '';
	}

	$out = array();
	$array_type = \blobfolio\common\cast::array_type($var);
	foreach ($var as $k=>$v) {
		$line = str_repeat("\t", $indents);
		if ('sequential' !== $array_type) {
			$line .= "'$k'=>";
		}
		if (is_array($v)) {
			$line .= 'array(' . array_to_php($v, $indents + 1) . ')';
		}
		else {
			$line .= "'$v'";
		}
		$out[] = $line;
	}

	return "\n" . implode(",\n", $out) . ",\n" . str_repeat("\t", $indents - 1);
}



/**
 * Get Manual MIME Rules (by MIME)
 *
 * We need to supplement our data to cover random oversights from the
 * main sources.
 *
 * @param string $mime MIME.
 * @return array|false More MIMEs or false.
 */
function get_manual_mime_types(string $mime) {
	$length = \blobfolio\common\mb::strlen($mime);
	if (!$length) {
		return false;
	}

	// Check for a full match first, which will usually be
	// the case.
	if (isset(MAGIC_LIST_BY_MIME[$mime])) {
		return MAGIC_LIST_BY_MIME[$mime];
	}

	// Otherwise some formats end up with a ton of offspring,
	// so we can look for partial matches at the beginning.
	foreach (MAGIC_LIST_BY_MIME as $k=>$v) {
		if (0 === \blobfolio\common\mb::strpos($mime, $k)) {
			return $v;
		}
	}

	return false;
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
 * @param bool $alias Treat as alias.
 * @return bool Status.
 */
function save_mime_ext_pair(string $mime='', string $ext='', string $source='', bool $alias=false) {
	global $mimes_by_extension;
	global $extensions_by_mime;
	global $consensus_ext;

	\blobfolio\common\ref\sanitize::file_extension($ext);
	\blobfolio\common\ref\sanitize::mime($mime);

	if (
		!strlen($ext) ||
		!strlen($mime) ||
		!strlen($source) ||
		('unknown' === $ext)
	) {
		return false;
	}

	if (!$alias) {
		if (!isset($consensus_ext[$ext])) {
			$consensus_ext[$ext] = array();
		}
		if (!isset($consensus_ext[$ext][$mime])) {
			$consensus_ext[$ext][$mime] = 1;
		}
		else {
			$consensus_ext[$ext][$mime]++;
		}
	}

	// Mimes by extension.
	if (!isset($mimes_by_extension[$ext])) {
		$mimes_by_extension[$ext] = EXT_DEFAULT;
		$mimes_by_extension[$ext]['ext'] = $ext;
	}

	if (!in_array($mime, $mimes_by_extension[$ext]['mime'], true)) {
		$mimes_by_extension[$ext]['mime'][] = $mime;
	}

	if ($alias && !in_array($mime, $mimes_by_extension[$ext]['alias'], true)) {
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

	// Are there hardcoded entries?
	if (false !== ($extra = get_manual_mime_types($mime))) {
		foreach ($extra as $m) {
			save_mime_ext_pair($m, $ext, 'Blobfolio', true);
		}
	}

	return true;
}



// -------------------------------------------------
// Begin!

if (!file_exists(SOURCE_PATH)) {
	mkdir(SOURCE_PATH, 0755);
}

if (file_exists(EXT_PATH)) {
	unlink(EXT_PATH);
}

if (file_exists(MIME_PATH)) {
	unlink(MIME_PATH);
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
foreach ($data as $k=>$v) {
	if (is_int($v)) {
		debug_stdout('      ++ ERROR retrieving ' . basename($k));
		continue;
	}

	debug_stdout('      ++ Parsing ' . basename($k) . '...');

	// Parse CSV.
	$v = explode_lines($v);

	foreach ($v as $raw) {
		$line = str_getcsv($raw);
		if (count($line) < 2 || !strlen($line[1])) {
			continue;
		}

		// 0 NAME.
		// 1 TEMPLATE.

		$urls[] = IANA_API . "/{$line[1]}";
	}
}

// Get templates to parse extensions.
debug_stdout('   ++ Fetching templates...');
$data = fetch_urls($urls);
$iana_override = array();
$iana_used = array();
foreach ($data as $k=>$v) {
	if (is_int($v)) {
		continue;
	}

	// Save file.
	$mime = \blobfolio\common\mb::strtolower(\blobfolio\common\mb::substr($k, \blobfolio\common\mb::strlen(IANA_API) + 1));

	// First some manual crap. I really wish IANA had consistency in
	// their formatting!
	$searches = array(
		'/suffix is "([\da-z\-_]{2,})"/ui',
		'/saved with the the file suffix ([\da-z\-_]{2,})./ui',
		'/ files: \.([\da-z\-_]{2,})./ui',
		'/file extension\(s\):\v\s*\*?\.([\da-z\-_]{2,})/ui',
	);
	foreach ($searches as $s) {
		preg_match($s, $v, $matches);
		if (count($matches)) {
			save_mime_ext_pair($mime, $matches[1], 'IANA');
		}
	}

	// Are there extensions?
	preg_match_all('/\s*File extension(\(s\))?\s*:\s*([\.,\da-z\h\-_]+)/ui', $v, $matches);
	if (count($matches[2])) {
		$raw = explode(',', $matches[2][0]);
		$raw = array_map('trim', $raw);
		\blobfolio\common\ref\mb::strtolower($raw);
		$raw = array_filter($raw, 'strlen');

		// First pass, clean up and split some more.
		foreach ($raw as $k=>$v) {
			$raw[$k] = str_replace(array('.', '*'), '', $v);
			$raw[$k] = preg_replace('/^\s+/u', '', $raw[$k]);
			$raw[$k] = preg_replace('/\s+$/u', '', $raw[$k]);

			if (false !== \blobfolio\common\mb::strpos($raw[$k], ' or ')) {
				$tmp = explode(' or ', $raw[$k]);
				$tmp = array_map('trim', $tmp);
				$tmp = array_filter($tmp, 'strlen');
				$tmp = array_values($tmp);
				if (count($tmp)) {
					$raw[$k] = $tmp[0];
					for ($x = 1; $x < count($tmp); $x++) {
						$raw[] = $tmp[$x];
					}
				}
			}

			if (!preg_match('/^[\da-z\-_]{2,}$/', $raw[$k])) {
				unset($raw[$k]);
			}
		}

		$raw = array_diff(
			$raw,
			array(
				'none',
				'undefined',
				'-none-',
				'na',
			)
		);

		if (!count($raw)) {
			continue;
		}

		// Second pass, pull out legitimate extensions!
		$exts = array();
		foreach ($raw as $ext) {
			if (preg_match('/^[\da-z]+[\da-z\-_]*[\da-z]+$/', $ext)) {
				$exts[] = $ext;
			}
		}
		if (count($exts)) {
			\blobfolio\common\ref\sanitize::mime($mime);
			foreach ($exts as $ext) {
				\blobfolio\common\ref\sanitize::file_extension($ext);
				if ($ext && $mime) {
					if (in_array($ext, $iana_used, true)) {
						if (isset($iana_override[$ext])) {
							unset($iana_override[$ext]);
						}
					}
					else {
						$iana_override[$ext] = $mime;
						$iana_used[] = $ext;
					}
					save_mime_ext_pair($mime, $ext, 'IANA');
				}
			}
		}
	}
}

// Sort by key to make searching more efficient later.
ksort($iana_override);



// -------------------------------------------------
// Apache

debug_stdout('');
debug_stdout('Apache', true);
debug_stdout('   ++ Fetching MIME list...');
$data = fetch_urls(array(APACHE_API));
if (!is_int($data[APACHE_API])) {
	// Parse output... much simpler than IANA.
	$lines = explode_lines($data[APACHE_API]);
	foreach ($lines as $line) {
		if (\blobfolio\common\mb::substr($line, 0, 1) === '#') {
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
else {
	debug_stdout('   ++ Fetch FAILED...');
}



// -------------------------------------------------
// Nginx

debug_stdout('');
debug_stdout('Nginx', true);
debug_stdout('   ++ Fetching MIME list...');
$data = fetch_urls(array(NGINX_API));
if (!is_int($data[NGINX_API])) {
	// Parse output... much simpler than IANA.
	$lines = explode_lines($data[NGINX_API]);
	foreach ($lines as $line) {
		if (
			\blobfolio\common\mb::substr($line, 0, 1) === '#' ||
			false !== \blobfolio\common\mb::strpos($line, '{') ||
			false !== \blobfolio\common\mb::strpos($line, '}')
		) {
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
else {
	debug_stdout('   ++ Fetch FAILED...');
}



// -------------------------------------------------
// Free Desktop

debug_stdout('');
debug_stdout('freedesktop.org', true);
debug_stdout('   ++ Fetching MIME list...');
$data = fetch_urls(array(FREEDESKTOP_API));
if (!is_int($data[FREEDESKTOP_API])) {
	$data = simplexml_load_string($data[FREEDESKTOP_API]);
	foreach ($data as $type) {
		// First, get the MIME(s).
		$mimes = array();
		foreach ($type->attributes() as $k=>$v) {
			if ('type' === $k) {
				$mimes[strval($v)] = false;
			}
		}

		// Might also be in aliases.
		if (isset($type->alias)) {
			foreach ($type->alias as $alias) {
				foreach ($alias->attributes() as $k=>$v) {
					if ('type' === $k) {
						$mimes[strval($v)] = true;
					}
				}
			}
		}

		// We should include parent-classes too.
		if (isset($type->{'sub-class-of'})) {
			foreach ($type->{'sub-class-of'}->attributes() as $k=>$v) {
				if ('type' === $k) {
					$mimes[strval($v)] = true;
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
				foreach ($mimes as $mime=>$alias) {
					save_mime_ext_pair($mime, $ext, 'freedesktop.org', $alias);
				}
			}
		}
	}
}
else {
	debug_stdout('   ++ Fetch FAILED...');
}



// -------------------------------------------------
// Tika

debug_stdout('');
debug_stdout('Apache Tika', true);
debug_stdout('   ++ Fetching MIME list...');
$data = fetch_urls(array(TIKA_API));
if (!is_int($data[TIKA_API])) {
	// SimpleXML doesn't like Tika's undefined namespaces, so let's just
	// remove them.
	$data[TIKA_API] = preg_replace('/<tika:(link|uti)>(.*)<\/tika:(link|uti)>/Us', '', $data[TIKA_API]);

	$data = simplexml_load_string($data[TIKA_API]);
	foreach ($data as $type) {
		// First, get the MIME(s).
		$mimes = array();
		foreach ($type->attributes() as $k=>$v) {
			if ('type' === $k) {
				$mimes[strval($v)] = false;
			}
		}

		// Might also be in aliases.
		if (isset($type->alias)) {
			foreach ($type->alias as $alias) {
				foreach ($alias->attributes() as $k=>$v) {
					if ('type' === $k) {
						$mimes[strval($v)] = true;
					}
				}
			}
		}

		// We should include parent-classes too.
		if (isset($type->{'sub-class-of'})) {
			foreach ($type->{'sub-class-of'}->attributes() as $k=>$v) {
				if ('type' === $k && !preg_match('/\/x\-tika/', (string) $v)) {
					$mimes[strval($v)] = true;
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
				foreach ($mimes as $mime=>$alias) {
					save_mime_ext_pair($mime, $ext, 'Tika', $alias);
				}
			}
		}
	}
}
else {
	debug_stdout('   ++ Fetch FAILED...');
}



// -------------------------------------------------
// A few manual entries.
$manual = array(
	'aac'=>array(
		'audio/x-hx-aac-adts',
	),
	'explain'=>array(
		'application/zip',
		'application/x-zip-compressed',
		'application/octet-stream',
	),
	'jp2'=>array(
		'image/jpx',
	),
	'jpf'=>array(
		'image/jp2',
		'image/jpeg2000',
		'image/jpeg2000-image',
		'image/x-jpeg2000-image',
	),
	'jpx'=>array(
		'image/jp2',
		'image/jpeg2000',
		'image/jpeg2000-image',
		'image/x-jpeg2000-image',
	),
);
foreach ($manual as $k=>$v) {
	foreach ($v as $v2) {
		save_mime_ext_pair($v2, $k, 'Blobfolio');
	}
}



// -------------------------------------------------
// Clean Up!

debug_stdout('');
debug_stdout('Finishing Touches', true);
debug_stdout('   ++ Cleaning data...');

// Default MIMEs are increasingly difficult to maintain. Haha.
$primary_override = array(
	'mid'=>'audio/midi',
	'otf'=>'font/otf',
	'pdf'=>'application/pdf',
	'png'=>'image/png',
);

// Calculate primary mime.
foreach ($mimes_by_extension as $k=>$v) {
	if (isset($primary_override[$k])) {
		$mimes_by_extension[$k]['primary'] = $primary_override[$k];
	}
	elseif (isset($iana_override[$k])) {
		$mimes_by_extension[$k]['primary'] = $iana_override[$k];
	}
	else {
		// Look for TYPE/EXT.
		foreach ($v['mime'] as $m) {
			if (
				('application/' !== substr($m, 0, 12)) &&
				preg_match('/^(' . implode('|', IANA_CATEGORIES) . ')\/' . preg_quote($k, '/') . '$/i', $m)
			) {
				$mimes_by_extension[$k]['primary'] = $m;
				break;
			}
		}
	}

	// Found one?
	if ($mimes_by_extension[$k]['primary']) {
		$mimes_by_extension[$k]['alias'] = array_values(array_diff($v['alias'], array($mimes_by_extension[$k]['primary'])));
		continue;
	}

	// Try consensus.
	if (isset($consensus_ext[$k])) {
		arsort($consensus_ext[$k]);
		$mimes_by_extension[$k]['primary'] = array_keys($consensus_ext[$k])[0];
		$mimes_by_extension[$k]['alias'] = array_values(array_diff($v['alias'], array($mimes_by_extension[$k]['primary'])));
		continue;
	}

	$possible = array_diff($v['mime'], $v['alias']);
	if (!count($possible)) {
		$possible = $v['mime'];
	}
	$possible = array_values($possible);

	$mimes_by_extension[$k]['primary'] = $possible[0];
	$mimes_by_extension[$k]['alias'] = array_values(array_diff($v['alias'], array($mimes_by_extension[$k]['primary'])));
}

// Now try to help out the reverse situation, best extension for a MIME.
// Here, we'll prioritize extensions that consider this MIME to be
// primary, while also ignoring pairings we manually specified.
foreach ($extensions_by_mime as $k=>$v) {
	usort($extensions_by_mime[$k]['ext'], function($a, $b) use($k) {
		global $mimes_by_extension;
		global $manual;

		$ext1 = $a;
		$ext2 = $b;

		$a = (
			isset($mimes_by_extension[$a]) &&
			($mimes_by_extension[$a]['primary'] === $k) &&
			(!isset($manual[$a]) || !in_array($k, $manual[$a], true))
		);
		$b = (
			isset($mimes_by_extension[$b]) &&
			($mimes_by_extension[$b]['primary'] === $k) &&
			(!isset($manual[$b]) || !in_array($k, $manual[$b], true))
		);

		// They are both primary contenders.
		if ($a === $b) {
			// Prefer the extension that is actually part of the MIME
			// type, but only if they're both 3-letter extensions.
			if (
				(strlen($ext1) === 3) &&
				(strlen($ext2) === 3) &&
				(substr_count($k, '/') === 1)
			) {
				list($k1, $k2) = explode('/', $k);
				$a = (false !== strpos($k2, $ext1));
				$b = (false !== strpos($k2, $ext2));

				if ($a === $b) {
					return 0;
				}
				elseif ($a) {
					return -1;
				}
				elseif ($b) {
					return 1;
				}
			}
			return 0;
		}

		// Prefer the primary match.
		return $a ? -1 : 1;
	});
}

debug_stdout('   ++ Sorting data...');
ksort($mimes_by_extension);
ksort($extensions_by_mime);

// Tighten up extension data for saving.
foreach ($mimes_by_extension as $k=>$v) {
	$out = array(
		'ext'=>$k,
		'mime'=>array(),
	);

	$data = array_unique(array_merge($v['mime'], $v['alias']));
	foreach ($v['mime'] as $v2) {
		if ($v2 !== $v['primary']) {
			$out['mime'][] = $v2;
		}
	}
	sort($out['mime']);

	array_unshift($out['mime'], $v['primary']);
	$mimes_by_extension[$k] = $out;
}

// Save data!
debug_stdout('   ++ Saving data...');
// JSON copy.
@file_put_contents(EXT_PATH, json_encode($mimes_by_extension));
@file_put_contents(MIME_PATH, json_encode($extensions_by_mime));

// PHP copy.
$replacements = array(
	'%GENERATED%'=>date('Y-m-d H:i:s'),
	'%EXTENSIONS_BY_MIME%'=>array_to_php($extensions_by_mime, 2),
	'%MIMES_BY_EXTENSION%'=>array_to_php($mimes_by_extension, 2),
);
$out = @file_get_contents(DATA_SRC);
$out = str_replace(array_keys($replacements), array_values($replacements), $out);
@file_put_contents(DATA_PATH, $out);

// Lastly, generate WordPress File!
debug_stdout('   ++ Saving WP media-mimes.php...');
$wp_data = array();
foreach ($mimes_by_extension as $k=>$v) {
	$wp_data[$k] = $v['mime'];
	sort($wp_data[$k]);
}

$wp_file = @file_get_contents(WP_SRC);
$wp_file = str_replace('%MIMES_BY_EXTENSION%', array_to_php($wp_data, 2), $wp_file);
@file_put_contents(WP_PATH, $wp_file);


$end = microtime(true);
debug_stdout('');
debug_stdout('Done!', true);
debug_stdout('   ++ Found ' . count($mimes_by_extension) . ' extensions, ' . count($extensions_by_mime) . ' MIME types.');
debug_stdout('   ++ Finished in ' . round($end - $start, 2) . ' seconds.');

