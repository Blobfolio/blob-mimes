<?php
/**
 * Compile MIME Data
 *
 * @package blobfolio/mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
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

namespace blobfolio\dev;

use \blobfolio\bob\utility;
use \blobfolio\common\data;
use \blobfolio\common\mb as v_mb;
use \blobfolio\common\ref\mb as r_mb;
use \blobfolio\common\ref\sanitize as r_sanitize;

class mimes extends \blobfolio\bob\base\build {
	const NAME = 'mimes';

	// Intl should catch this, but just in case...
	const DOWNLOADS = array(
		'http://hg.nginx.org/nginx/raw-file/default/conf/mime.types',
		'https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in',
		'https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types',
		'https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml',
		'https://www.iana.org/assignments/media-types/application.csv',
		'https://www.iana.org/assignments/media-types/audio.csv',
		'https://www.iana.org/assignments/media-types/font.csv',
		'https://www.iana.org/assignments/media-types/image.csv',
		'https://www.iana.org/assignments/media-types/message.csv',
		'https://www.iana.org/assignments/media-types/model.csv',
		'https://www.iana.org/assignments/media-types/multipart.csv',
		'https://www.iana.org/assignments/media-types/text.csv',
		'https://www.iana.org/assignments/media-types/video.csv',
	);

	const REQUIRED_FUNCTIONS = array('simplexml_load_string');

	// We aren't using binaries or build steps.
	const SKIP_BINARY_DEPENDENCIES = true;
	const SKIP_BUILD = false;
	const SKIP_FILE_DEPENDENCIES = true;
	const SKIP_PACKAGE = true;

	// IANA does not have a central dataset. We have to do a lot of
	// parsing and downloading.
	const IANA_BASE = 'https://www.iana.org/assignments/media-types/';
	const IANA_CATEGORIES = array(
		'application',
		'audio',
		'font',
		'image',
		'message',
		'model',
		'multipart',
		'text',
		'video',
	);

	// Other URLs.
	const APACHE_DATA = 'https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types';
	const FREEDESKTOP_DATA = 'https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in';
	const NGINX_DATA = 'http://hg.nginx.org/nginx/raw-file/default/conf/mime.types';
	const TIKA_DATA = 'https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml';

	// Input/Output paths.
	const BIN_OUT = BOB_ROOT_DIR . 'bin/';
	const DATA_OUT = BOB_ROOT_DIR . 'lib/blobfolio/mimes/data.php';
	const DATA_TEMPLATE = BOB_BUILD_DIR . 'skel/data.template';
	const WP_OUT = BOB_ROOT_DIR . 'wp/lib/blobfolio/wp/bm/mime/aliases.php';
	const WP_TEMPLATE = BOB_BUILD_DIR . 'skel/wp.template';

	// Template for MxE entry.
	const MIMES_BY_EXTENSION = array(
		'ext'=>'',
		'mime'=>array(),
		'source'=>array(),
		'alias'=>array(),
		'primary'=>array(),
	);

	// Template for ExM entry.
	const EXTENSIONS_BY_MIME = array(
		'mime'=>'',
		'ext'=>array(),
		'source'=>array(),
	);

	// Manual MIME aliases.
	const MAGIC_LIST_BY_MIME = array(
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
	);

	// Source: Blobfolio.
	const MAGIC_LIST_BLOBFOLIO = array(
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

	// Primary MIME overrides.
	const MAGIC_LIST_PRIMARY_MIME = array(
		'mid'=>'audio/midi',
		'otf'=>'font/otf',
		'pdf'=>'application/pdf',
		'png'=>'image/png',
	);

	// IANA is the most authoritative source.
	protected static $iana_local = array();
	protected static $iana_used = array();
	protected static $iana_override = array();

	// We'll try to build a consensus for "primary" extensions.
	protected static $consensus_ext = array();

	// MIME types by extension.
	protected static $mxe = array();

	// File extensions by MIME type.
	protected static $exm = array();



	// -----------------------------------------------------------------
	// Build
	// -----------------------------------------------------------------

	/**
	 * Pre-Build Tasks
	 *
	 * @return void Nothing.
	 */
	protected static function pre_build_tasks() {
		// We need to get more from IANA.
		utility::log('Locating additional IANA resources…');

		// Parse each category.
		$more = array();
		foreach (static::IANA_CATEGORIES as $v) {
			$url = static::IANA_BASE . "{$v}.csv";
			$tmp = file_get_contents(static::$downloads[$url]);
			$tmp = utility::doc_to_lines($tmp);

			$num = 0;
			foreach ($tmp as $raw) {
				$num++;

				// Skip headers.
				if ($num > 1) {
					$line = str_getcsv($raw);
					if (isset($line[1]) && $line[1]) {
						$more[] = static::IANA_BASE . $line[1];
					}
				}
			}
		}

		utility::log('Downloading ' . count($more) . ' additional files. Haha.');

		static::$iana_local = utility::get_remote($more);
	}

	/**
	 * Build Tasks
	 *
	 * @return void Nothing.
	 */
	protected static function build_tasks() {
		static::build_iana();
		static::build_apache();
		static::build_nginx();
		static::build_freedesktop();
		static::build_tika();
		static::build_blobfolio();

		static::build_primary_mime();
		static::build_primary_ext();

		static::build_cleanup();

		// Save copies as JSON.
		utility::log('Exporting JSON…');

		file_put_contents(static::BIN_OUT . 'extensions_by_mime.json', json_encode(static::$exm));
		file_put_contents(static::BIN_OUT . 'mimes_by_extension.json', json_encode(static::$mxe));

		// Export the main data used by this library.
		utility::log('Exporting library data…');

		$content = file_get_contents(static::DATA_TEMPLATE);

		$replacements = array(
			'%GENERATED%'=>date('Y-m-d H:i:s'),
			'%EXTENSIONS_BY_MIME%'=>utility::array_to_php(static::$exm, 2),
			'%MIMES_BY_EXTENSION%'=>utility::array_to_php(static::$mxe, 2),
		);

		$content = str_replace(array_keys($replacements), array_values($replacements), $content);
		file_put_contents(static::DATA_OUT, $content);

		// Also generate aliases for the WordPress plugin.
		utility::log('Exporting WP plugin data…');

		$content = file_get_contents(static::WP_TEMPLATE);

		// WordPress needs simpler data.
		$data = array();
		foreach (static::$mxe as $k=>$v) {
			$data[$k] = $v['mime'];
			sort($data[$k]);
		}

		$content = str_replace('%MIMES_BY_EXTENSION%', utility::array_to_php($data, 2), $content);
		file_put_contents(static::WP_OUT, $content);

		// Finally, report how many MIMEs and Extensions we found!
		$count = number_format(count(static::$mxe), 0, '.', ',');
		utility::log("Total file extensions: $count", 'success');
		$count = number_format(count(static::$exm), 0, '.', ',');
		utility::log("Total MIME types: $count", 'success');
	}

	/**
	 * Build IANA
	 *
	 * The best source, and the worst structure. Haha.
	 *
	 * @return void Nothing.
	 */
	protected static function build_iana() {
		utility::log('Parsing IANA data…');

		// We have to do some dirty RegExp parsing. These patterns take
		// care of most of it.
		$patterns = array(
			'/suffix is "([\da-z\-_]{2,})"/ui',
			'/saved with the the file suffix ([\da-z\-_]{2,})./ui',
			'/ files: \.([\da-z\-_]{2,})./ui',
			'/file extension\(s\):\v\s*\*?\.([\da-z\-_]{2,})/ui',
		);

		$base_length = strlen(static::IANA_BASE);

		// Run through each of the million files!
		foreach (static::$iana_local as $url=>$file) {
			if (!$file) {
				continue;
			}

			// Parse out the MIME type from the URL.
			$mime = substr($url, $base_length);
			r_sanitize::mime($mime);
			if (!$mime) {
				continue;
			}

			$content = file_get_contents($file);

			// See if our patterns turn anything up.
			foreach ($patterns as $pattern) {
				preg_match($pattern, $content, $matches);
				if (isset($matches[1])) {
					static::save_pair($mime, $matches[1], 'IANA');
				}
			}

			// Look for extensions too.
			preg_match_all('/\s*File extension(\(s\))?\s*:\s*([\.,\da-z\h\-_]+)/ui', $content, $matches);
			if (count($matches[2])) {
				$raw = explode(',', $matches[2][0]);
				r_mb::trim($raw);
				r_mb::strtolower($raw);
				$raw = array_values(array_filter($raw, 'strlen'));

				// If there aren't any, we're done.
				if (!count($raw)) {
					continue;
				}

				// First pass, clean up data.
				foreach ($raw as $k=>$v) {
					$raw[$k] = str_replace(array('.', '*'), '', $raw[$k]);
					r_mb::trim($raw[$k]);

					// Split "or".
					if (false !== strpos($raw[$k], ' or ')) {
						$tmp = explode(' or ', $raw[$k]);
						r_mb::trim($tmp);
						$tmp = array_values(array_filter($tmp, 'strlen'));
						if (count($tmp)) {
							$raw[$k] = $tmp[0];
							for ($x = 1; $x < count($tmp); ++$x) {
								$raw[] = $tmp[$x];
							}
						}
					}

					// Get rid of ugly data.
					if (!preg_match('/^[\da-z\-_]{2,}$/', $raw[$k])) {
						unset($raw[$k]);
					}
				}

				// Remove non-entries.
				$raw = array_diff(
					$raw,
					array(
						'-none-',
						'na',
						'none',
						'undefined',
						'unknown',
					)
				);
				if (!count($raw)) {
					continue;
				}

				// Second pass, grab extensions!
				$exts = array();
				foreach ($raw as $ext) {
					if (preg_match('/^[\da-z]+[\da-z\-_]*[\da-z]+$/', $ext)) {
						r_sanitize::file_extension($ext);
						if ($ext) {
							$exts[] = $ext;
						}
					}
				}
				foreach ($exts as $ext) {
					// Have we already used this IANA extension for
					// something else? That kills its authority.
					if (isset(static::$iana_used[$ext])) {
						if (isset(static::$iana_override[$ext])) {
							unset(static::$iana_override[$ext]);
						}
					}
					// Otherwise it might be nice.
					else {
						static::$iana_override[$ext] = $mime;
						static::$iana_used[$ext] = true;
					}

					static::save_pair($mime, $ext, 'IANA');
				}
			}
		}

		// Sort for later.
		ksort(static::$iana_override);
		ksort(static::$iana_used);
	}

	/**
	 * Build Apache
	 *
	 * @return void Nothing.
	 */
	protected static function build_apache() {
		utility::log('Parsing Apache data…');

		$content = file_get_contents(static::$downloads[static::APACHE_DATA]);
		$content = utility::doc_to_lines($content);
		foreach ($content as $line) {
			// Skip comments.
			if (0 === strpos($line, '#')) {
				continue;
			}

			$line = preg_replace('/\s+/u', ' ', $line);
			$line = explode(' ', $line);
			if (!isset($line[1])) {
				continue;
			}

			$mime = $line[0];
			unset($line[0]);
			foreach ($line as $ext) {
				static::save_pair($mime, $ext, 'Apache');
			}
		}
	}

	/**
	 * Build Nginx
	 *
	 * @return void Nothing.
	 */
	protected static function build_nginx() {
		utility::log('Parsing Nginx data…');

		$content = file_get_contents(static::$downloads[static::NGINX_DATA]);
		$content = utility::doc_to_lines($content);
		foreach ($content as $line) {
			// Skip comments and configs.
			if (
				(0 === strpos($line, '#')) ||
				(false !== strpos($line, '{')) ||
				(false !== strpos($line, '}'))
			) {
				continue;
			}

			$line = rtrim($line, ';');
			$line = preg_replace('/\s+/u', ' ', $line);
			$line = explode(' ', $line);
			if (!isset($line[1])) {
				continue;
			}

			$mime = $line[0];
			unset($line[0]);
			foreach ($line as $ext) {
				static::save_pair($mime, $ext, 'Nginx');
			}
		}
	}

	/**
	 * Build Freedesktop.org
	 *
	 * @return void Nothing.
	 */
	protected static function build_freedesktop() {
		utility::log('Parsing Freedesktop.org data…');

		$content = file_get_contents(static::$downloads[static::FREEDESKTOP_DATA]);
		static::parse_xml_mimes($content, 'freedesktop.org');
	}

	/**
	 * Build Tika
	 *
	 * @return void Nothing.
	 */
	protected static function build_tika() {
		utility::log('Parsing Tika data…');

		$content = file_get_contents(static::$downloads[static::TIKA_DATA]);

		// Tika uses the FD XML format, but their tika namespace
		// crashes SimpleXML, so we have to pre-strip.
		$content = preg_replace('/<tika:(link|uti)>(.*)<\/tika:(link|uti)>/Us', '', $content);

		static::parse_xml_mimes($content, 'Tika');
	}

	/**
	 * Build Blobfolio
	 *
	 * @return void Nothing.
	 */
	protected static function build_blobfolio() {
		utility::log('Parsing Blobfolio data…');

		// Ours is the easiest.
		foreach (static::MAGIC_LIST_BLOBFOLIO as $k=>$v) {
			foreach ($v as $v2) {
				static::save_pair($v2, $k, 'Blobfolio');
			}
		}
	}

	/**
	 * Primary MIME
	 *
	 * It isn't always obvious which MIME type is "realest".
	 *
	 * @return void Nothing.
	 */
	protected static function build_primary_mime() {
		utility::log('Calculating primary MIME entries…');

		foreach (static::$mxe as $k=>$v) {
			// In our magic list.
			if (isset(static::MAGIC_LIST_PRIMARY_MIME[$k])) {
				static::$mxe[$k]['primary'] = static::MAGIC_LIST_PRIMARY_MIME[$k];
			}
			// Provided by IANA.
			elseif (isset(static::$iana_override[$k])) {
				static::$mxe[$k]['primary'] = static::$iana_override[$k];
			}
			else {
				// Prefer a Type/Ext direct hit.
				$pattern = '#^(' . implode('|', static::IANA_CATEGORIES) . ')';
				foreach ($v['mime'] as $mime) {
					if (
						(0 !== strpos($mime, 'application/')) &&
						preg_match($pattern . preg_quote($k, '#') . '$#', $mime)
					) {
						static::$mxe[$k]['primary'] = $mime;
						break;
					}
				}
			}

			// Nothing yet? Try consensus.
			if (!static::$mxe[$k]['primary'] && isset(static::$consensus_ext[$k])) {
				arsort(static::$consensus_ext[$k]);
				$possible = array_keys(static::$consensus_ext[$k]);
				static::$mxe[$k]['primary'] = array_shift($possible);
			}

			// If we still don't have one, let's look for a MIME that
			// isn't listed as an alias, or whatever we can. Haha.
			if (!static::$mxe[$k]['primary']) {
				$possible = array_diff($v['mime'], $v['alias']);
				if (!count($possible)) {
					$possible = $v['mime'];
				}
				static::$mxe[$k]['primary'] = data::array_pop_top($possible);
			}

			// Make sure primary isn't in the alias list.
			static::$mxe[$k]['alias'] = array_values(array_diff(
				$v['alias'],
				array(static::$mxe[$k]['primary'])
			));
		}

		utility::log('Sorting data…');
		ksort(static::$mxe);
	}

	/**
	 * Primary Extension
	 *
	 * The reverse situation is trickier because one MIME type might see
	 * a lot of different file extensions. Still, carry on...
	 *
	 * @return void Nothing.
	 */
	protected static function build_primary_ext() {
		utility::log('Calculating primary file extensions…');

		foreach (static::$exm as $k=>$v) {
			// Extensions do not have aliases, so basically we're just
			// trying to sort by relevance.
			usort(static::$exm[$k]['ext'], function($a, $b) use($k) {
				$ext1 = $a;
				$ext2 = $b;

				// If the MIME is primary, that's a positive sign.
				$a = (
					isset(static::$mxe[$a]) &&
					($k === static::$mxe[$a]['primary']) &&
					(
						!isset(static::MAGIC_LIST_BLOBFOLIO[$a]) ||
						!in_array($k, static::MAGIC_LIST_BLOBFOLIO[$a], true)
					)
				);
				$b = (
					isset(static::$mxe[$b]) &&
					($k === static::$mxe[$b]['primary']) &&
					(
						!isset(static::MAGIC_LIST_BLOBFOLIO[$b]) ||
						!in_array($k, static::MAGIC_LIST_BLOBFOLIO[$b], true)
					)
				);

				// Sub-sort if the two are equal.
				if ($a === $b) {
					// Prefer the extension that is part of the MIME
					// type, provided they're both 3 letters long.
					if (
						(3 === strlen($ext1)) &&
						(3 === strlen($ext2)) &&
						(1 === substr_count($k, '/'))
					) {
						list($type, $subtype) = explode('/', $k);
						$a = (false !== strpos($subtype, $ext1));
						$b = (false !== strpos($subtype, $ext2));

						if ($a === $b) {
							return 0;
						}
						return $a ? -1 : 1;
					}

					return 0;
				}

				return $a ? -1 : 1;
			});
		}

		utility::log('Sorting data…');
		ksort(static::$exm);
	}

	/**
	 * Build Cleanup
	 *
	 * @return void Nothing.
	 */
	protected static function build_cleanup() {
		utility::log('Tidying up data…');

		// We don't actually need all the data we've got.
		foreach (static::$mxe as $k=>$v) {
			$tmp = array(
				'ext'=>$k,
				'mime'=>array(),
			);

			$data = array_unique(array_merge($v['mime'], $v['alias']));
			$data = array_diff($data, array($v['primary']));
			foreach ($data as $v2) {
				$tmp['mime'][] = $v2;
			}
			sort($tmp['mime']);

			// Add the primary to the top.
			array_unshift($tmp['mime'], $v['primary']);
			static::$mxe[$k] = $tmp;
		}
	}

	// ----------------------------------------------------------------- end build



	// -----------------------------------------------------------------
	// Helpers
	// -----------------------------------------------------------------

	/**
	 * Parse XML MIME Data
	 *
	 * This is used by Freedesktop and Tika.
	 *
	 * @param string $xml XML.
	 * @param string $source Source.
	 */
	protected static function parse_xml_mimes(string $xml, string $source) {
		$xml = simplexml_load_string($xml);
		foreach ($xml as $type) {
			// First, get the MIME(s).
			$mimes = array();
			foreach ($type->attributes() as $k=>$v) {
				$v = (string) $v;
				if ('type' === $k) {
					$mimes[$v] = false;
				}
			}

			// There could also be aliases.
			if (isset($type->alias)) {
				foreach ($type->alias as $alias) {
					foreach ($alias->attributes() as $k=>$v) {
						$v = (string) $v;
						if ('type' === $k) {
							$mimes[$v] = true;
						}
					}
				}
			}

			// Include parent classes too.
			if (isset($type->{'sub-class-of'})) {
				foreach ($type->{'sub-class-of'}->attributes() as $k=>$v) {
					$v = (string) $v;
					if (('type' === $k) && (false === strpos($v, '/x-tika'))) {
						$mimes[strval($v)] = true;
					}
				}
			}

			// Extensions are hidden in globs.
			$exts = array();
			if (isset($type->glob)) {
				foreach ($type->glob as $glob) {
					foreach ($glob->attributes() as $k=>$v) {
						$v = (string) $v;
						if ('pattern' === $k) {
							$v = ltrim($v, '.*');
							if ($v === '4th') {
							}
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
						static::save_pair($mime, $ext, $source, $alias);
					}
				}
			}
		}
	}

	/**
	 * Save MIME/Extension Pair
	 *
	 * @param string $mime MIME.
	 * @param string $ext Extension.
	 * @param string $source Source.
	 * @param bool $alias Alias.
	 * @return void Nothing.
	 */
	protected static function save_pair(string $mime, string $ext, string $source, bool $alias=false) {
		r_sanitize::mime($mime);
		r_sanitize::file_extension($ext);

		// Ignore bad data.
		if (!$ext || !$mime || !$source || ('unknown' === $ext)) {
			return;
		}

		// Consensus building.
		if (!$alias) {
			if (!isset(static::$consensus_ext[$ext])) {
				static::$consensus_ext[$ext] = array();
			}
			if (!isset(static::$consensus_ext[$ext][$mime])) {
				static::$consensus_ext[$ext][$mime] = 0;
			}
			++static::$consensus_ext[$ext][$mime];
		}

		// MIMEs by extension.
		if (!isset(static::$mxe[$ext])) {
			static::$mxe[$ext] = static::MIMES_BY_EXTENSION;
			static::$mxe[$ext]['ext'] = $ext;
		}

		// Add to MIME list.
		if (!in_array($mime, static::$mxe[$ext]['mime'], true)) {
			static::$mxe[$ext]['mime'][] = $mime;
		}

		// Add to alias list.
		if ($alias && !in_array($mime, static::$mxe[$ext]['alias'], true)) {
			static::$mxe[$ext]['alias'][] = $mime;
		}

		// Note the source.
		if (!in_array($source, static::$mxe[$ext]['source'], true)) {
			static::$mxe[$ext]['source'][] = $source;
		}

		// Extensions by MIME.
		if (!isset(static::$exm[$mime])) {
			static::$exm[$mime] = static::EXTENSIONS_BY_MIME;
			static::$exm[$mime]['mime'] = $mime;
		}

		// Add extension.
		if (!in_array($ext, static::$exm[$mime]['ext'], true)) {
			static::$exm[$mime]['ext'][] = $ext;
		}

		// Add source.
		if (!in_array($source, static::$exm[$mime]['source'], true)) {
			static::$exm[$mime]['source'][] = $source;
		}

		// Hard coded entries?
		if (false !== ($extra = static::get_manual_mimes($mime))) {
			foreach ($extra as $m) {
				static::save_pair($m, $ext, 'Blobfolio', true);
			}
		}
	}

	/**
	 * Manual MIME Overrides
	 *
	 * @param string $mime MIME.
	 * @return bool|array MIMEs or false.
	 */
	protected static function get_manual_mimes(string $mime) {
		// Check for a full match first.
		if (isset(static::MAGIC_LIST_BY_MIME[$mime])) {
			return static::MAGIC_LIST_BY_MIME[$mime];
		}

		// Otherwise return partial matches.
		foreach (static::MAGIC_LIST_BY_MIME as $k=>$v) {
			if (0 === strpos($mime, $k)) {
				return $v;
			}
		}

		return false;
	}

	// ----------------------------------------------------------------- end helpers
}
