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
 * @see {https://raw.githubusercontent.com/nginx/nginx/master/conf/mime.types}
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

use blobfolio\bob\format;
use blobfolio\bob\io;
use blobfolio\bob\log;
use blobfolio\common\data;
use blobfolio\common\file as v_file;
use blobfolio\common\ref\mb as r_mb;
use blobfolio\common\ref\sanitize as r_sanitize;

class mimes extends \blobfolio\bob\base\mike {
	// Project Name.
	const NAME = 'blob-mimes';
	const DESCRIPTION = 'blob-mimes is a comprehensive MIME and file extension tool for PHP.';
	const CONFIRMATION = '';
	const SLUG = 'blob-mimes';

	// Runtime requirements.
	const REQUIRED_FUNCTIONS = array('simplexml_load_string');

	const REQUIRED_DOWNLOADS = array(
		'https://raw.githubusercontent.com/nginx/nginx/master/conf/mime.types',
		'https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in',
		'https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types',
		'https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml',
	);

	// Automatic setup.
	const CLEAN_ON_SUCCESS = false;			// Delete tmp/bob when done.
	const SHITLIST = null;					// Specific shitlist.

	// Functions to run to complete the build, in order, grouped by
	// heading.
	const ACTIONS = array(
		'Updating Data'=>array(
			'build_iana_resources',
			'build_iana',
			'build_apache',
			'build_nginx',
			'build_freedesktop',
			'build_tika',
			'build_blobfolio',
			'build_primary_mime',
			'build_primary_ext',
			'build_cleanup',
			'release',
		),
	);

	// Unlike the other sources, IANA splits its data across thousands
	// of different files with no programmatic consistency to formatting
	// to help us out. Haha.
	const IANA_BASE = 'https://www.iana.org/assignments/media-types/';
	const IANA_RSYNC = 'rsync://rsync.iana.org/assignments/media-types/';
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
	const NGINX_DATA = 'https://raw.githubusercontent.com/nginx/nginx/master/conf/mime.types';
	const TIKA_DATA = 'https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml';

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
		'html'=>'text/html',
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
	// Data Sources
	// -----------------------------------------------------------------

	/**
	 * Additional IANA Sources
	 *
	 * @return void Nothing.
	 */
	protected static function build_iana_resources() {
		log::print('Downloading IANA data…');

		// Make a directory to hold the data.
		static::$iana_local = io::make_dir();

		// We need to use rsync to fetch the files.
		$cmd = io::get_command(
			'rsync',
			array(
				'-avz',
				\escapeshellcmd(static::IANA_RSYNC),
				\escapeshellcmd(static::$iana_local),
			)
		);
		if (! io::exec($cmd)) {
			log::error("The data sync failed. Make sure \033[1mrsync\033[0m is installed.");
		}
	}

	/**
	 * Build IANA
	 *
	 * The best source, and the worst structure. Haha.
	 *
	 * @return void Nothing.
	 */
	protected static function build_iana() {
		log::print('Parsing IANA data…');

		// We have to do some dirty RegExp parsing. These patterns take
		// care of most of it.
		$patterns = array(
			'/suffix is "([\da-z\-_]{2,})"/ui',
			'/saved with the the file suffix ([\da-z\-_]{2,})./ui',
			'/ files: \.([\da-z\-_]{2,})./ui',
			'/file extension\(s\):\v\s*\*?\.([\da-z\-_]{2,})/ui',
		);

		$base_length = \strlen(static::$iana_local);
		foreach (static::IANA_CATEGORIES as $category) {
			$files = v_file::scandir(static::$iana_local . "$category/", true, false);
			if (! isset($files[0])) {
				continue;
			}

			log::print("Parsing IANA {$category}/ types…");

			foreach ($files as $file) {
				// The MIME is the parent folder and file.
				$mime = \substr($file, $base_length);
				r_sanitize::mime($mime);
				if (! $mime) {
					continue;
				}

				$content = \file_get_contents($file);

				// See if our patterns turn anything up.
				foreach ($patterns as $pattern) {
					\preg_match($pattern, $content, $matches);
					if (isset($matches[1])) {
						static::save_pair($mime, $matches[1], 'IANA');
					}
				}

				// Look for extensions too.
				\preg_match_all(
					'/\s*File extension(\(s\))?\s*:\s*([\.,\da-z\h\-_]+)/ui',
					$content,
					$matches
				);
				if (\count($matches[2])) {
					$raw = \explode(',', $matches[2][0]);
					r_mb::trim($raw);
					r_mb::strtolower($raw);
					$raw = \array_values(\array_filter($raw, 'strlen'));

					// If there aren't any, we're done.
					if (! \count($raw)) {
						continue;
					}

					// First pass, clean up data.
					foreach ($raw as $k=>$v) {
						$raw[$k] = \str_replace(array('.', '*'), '', $raw[$k]);
						r_mb::trim($raw[$k]);

						// Split "or".
						if (false !== \strpos($raw[$k], ' or ')) {
							$tmp = \explode(' or ', $raw[$k]);
							r_mb::trim($tmp);
							$tmp = \array_values(\array_filter($tmp, 'strlen'));
							if (\count($tmp)) {
								$raw[$k] = $tmp[0];
								for ($x = 1; $x < \count($tmp); ++$x) {
									$raw[] = $tmp[$x];
								}
							}
						}

						// Get rid of ugly data.
						if (! \preg_match('/^[\da-z\-_]{2,}$/', $raw[$k])) {
							unset($raw[$k]);
						}
					}

					// Remove non-entries.
					$raw = \array_diff(
						$raw,
						array(
							'-none-',
							'na',
							'none',
							'undefined',
							'unknown',
						)
					);
					if (! \count($raw)) {
						continue;
					}

					// Second pass, grab extensions!
					$exts = array();
					foreach ($raw as $ext) {
						if (\preg_match('/^[\da-z]+[\da-z\-_]*[\da-z]+$/', $ext)) {
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
			} // Each file.
		} // Each category.

		// Sort for later.
		\ksort(static::$iana_override);
		\ksort(static::$iana_used);

		log::print('Cleaning up…');
		v_file::rmdir(static::$iana_local);
	}

	/**
	 * Build Apache
	 *
	 * @return void Nothing.
	 */
	protected static function build_apache() {
		log::print('Parsing Apache data…');

		$content = io::get_url(static::APACHE_DATA);
		$content = format::lines_to_array($content);
		foreach ($content as $line) {
			// Skip comments.
			if (0 === \strpos($line, '#')) {
				continue;
			}

			$line = \preg_replace('/\s+/u', ' ', $line);
			$line = \explode(' ', $line);
			if (! isset($line[1])) {
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
		log::print('Parsing Nginx data…');

		$content = io::get_url(static::NGINX_DATA);
		$content = format::lines_to_array($content);

		foreach ($content as $line) {
			// Skip comments and configs.
			if (
				(0 === \strpos($line, '#')) ||
				(false !== \strpos($line, '{')) ||
				(false !== \strpos($line, '}'))
			) {
				continue;
			}

			$line = \rtrim($line, ';');
			$line = \trim(\preg_replace('/\s+/u', ' ', $line));
			$line = \explode(' ', $line);
			if (! isset($line[1])) {
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
		log::print('Parsing Freedesktop.org data…');

		$content = io::get_url(static::FREEDESKTOP_DATA);
		static::parse_xml_mimes($content, 'freedesktop.org');
	}

	/**
	 * Build Tika
	 *
	 * @return void Nothing.
	 */
	protected static function build_tika() {
		log::print('Parsing Tika data…');

		$content = io::get_url(static::TIKA_DATA);

		// Tika uses the FD XML format, but their tika namespace
		// crashes SimpleXML, so we have to pre-strip.
		$content = \preg_replace(
			'/<tika:(link|uti)>(.*)<\/tika:(link|uti)>/Us',
			'',
			$content
		);

		static::parse_xml_mimes($content, 'Tika');
	}

	/**
	 * Build Blobfolio
	 *
	 * @return void Nothing.
	 */
	protected static function build_blobfolio() {
		log::print('Parsing Blobfolio data…');

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
		log::print('Calculating primary MIME entries…');

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
				$pattern = '#^(' . \implode('|', static::IANA_CATEGORIES) . ')';
				foreach ($v['mime'] as $mime) {
					if (
						(0 !== \strpos($mime, 'application/')) &&
						\preg_match($pattern . \preg_quote($k, '#') . '$#', $mime)
					) {
						static::$mxe[$k]['primary'] = $mime;
						break;
					}
				}
			}

			// Nothing yet? Try consensus.
			if (! static::$mxe[$k]['primary'] && isset(static::$consensus_ext[$k])) {
				\arsort(static::$consensus_ext[$k]);
				$possible = \array_keys(static::$consensus_ext[$k]);
				static::$mxe[$k]['primary'] = \array_shift($possible);
			}

			// If we still don't have one, let's look for a MIME that
			// isn't listed as an alias, or whatever we can. Haha.
			if (! static::$mxe[$k]['primary']) {
				$possible = \array_diff($v['mime'], $v['alias']);
				if (! \count($possible)) {
					$possible = $v['mime'];
				}
				static::$mxe[$k]['primary'] = data::array_pop_top($possible);
			}

			// Make sure primary isn't in the alias list.
			static::$mxe[$k]['alias'] = \array_values(\array_diff(
				$v['alias'],
				array(static::$mxe[$k]['primary'])
			));
		}

		log::print('Sorting data…');
		\ksort(static::$mxe);
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
		log::print('Calculating primary file extensions…');

		foreach (static::$exm as $k=>$v) {
			// Extensions do not have aliases, so basically we're just
			// trying to sort by relevance.
			\usort(static::$exm[$k]['ext'], function($a, $b) use($k) {
				$ext1 = $a;
				$ext2 = $b;

				// If the MIME is primary, that's a positive sign.
				$a = (
					isset(static::$mxe[$a]) &&
					($k === static::$mxe[$a]['primary']) &&
					(
						! isset(static::MAGIC_LIST_BLOBFOLIO[$a]) ||
						! \in_array($k, static::MAGIC_LIST_BLOBFOLIO[$a], true)
					)
				);
				$b = (
					isset(static::$mxe[$b]) &&
					($k === static::$mxe[$b]['primary']) &&
					(
						! isset(static::MAGIC_LIST_BLOBFOLIO[$b]) ||
						! \in_array($k, static::MAGIC_LIST_BLOBFOLIO[$b], true)
					)
				);

				// Sub-sort if the two are equal.
				if ($a === $b) {
					// Prefer the extension that is part of the MIME
					// type, provided they're both 3 letters long.
					if (
						(3 === \strlen($ext1)) &&
						(3 === \strlen($ext2)) &&
						(1 === \substr_count($k, '/'))
					) {
						list($type, $subtype) = \explode('/', $k);
						$a = (false !== \strpos($subtype, $ext1));
						$b = (false !== \strpos($subtype, $ext2));

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

		log::print('Sorting data…');
		\ksort(static::$exm);
	}

	/**
	 * Build Cleanup
	 *
	 * @return void Nothing.
	 */
	protected static function build_cleanup() {
		log::print('Tidying up data…');

		// We don't actually need all the data we've got.
		foreach (static::$mxe as $k=>$v) {
			$tmp = array(
				'ext'=>$k,
				'mime'=>array(),
			);

			$data = \array_unique(\array_merge($v['mime'], $v['alias']));
			$data = \array_diff($data, array($v['primary']));

			// A lot of file types are really just XML, JSON, and/or
			// some other generic text thing that tend to be detected as
			// text/plain, text/xml, etc. We'll add the generic types to
			// each list as needed.
			$xml_generic = false;
			$json_generic = false;
			$text_generic = false;

			foreach ($data as $v2) {
				$tmp['mime'][] = $v2;

				// Generic XML?
				if (
					! $xml_generic &&
					(
						('application/xml' === $v2) ||
						('text/xml' === $v2) ||
						('+xml' === \substr($v2, -4))
					)
				) {
					$xml_generic = true;
					$text_generic = true;
				}

				// Generic JSON?
				if (
					! $json_generic &&
					(
						('application/json' === $v2) ||
						('+json' === \substr($v2, -5))
					)
				) {
					$json_generic = true;
					$text_generic = true;
				}

				// Generic text?
				if (! $text_generic && (0 === \strpos($v2, 'text/'))) {
					$text_generic = true;
				}
			}

			// Add generic types.
			$generic = array();

			if ($xml_generic) {
				$generic[] = 'text/xml';
				$generic[] = 'application/xml';
			}

			if ($json_generic) {
				$generic[] = 'text/json';
				$generic[] = 'application/json';
			}

			if ($text_generic) {
				$generic[] = 'text/plain';
			}

			foreach ($generic as $v2) {
				if (
					($v2 !== $v['primary']) &&
					! \in_array($v2, $tmp['mime'], true)
				) {
					$tmp['mime'][] = $v2;
				}
			}

			\sort($tmp['mime']);

			// Add the primary to the top.
			\array_unshift($tmp['mime'], $v['primary']);
			static::$mxe[$k] = $tmp;
		}
	}

	// ----------------------------------------------------------------- end data



	// -----------------------------------------------------------------
	// Release
	// -----------------------------------------------------------------

	/**
	 * Package Release!
	 *
	 * @return void Nothing.
	 */
	protected static function release() {
		// Save copies as JSON.
		log::print('Exporting JSON…');

		// Define some paths.
		$bin_out = \dirname(\BOB_ROOT_DIR) . '/bin/';
		$data_out = \dirname(\BOB_ROOT_DIR) . '/lib/blobfolio/mimes/data.php';
		$data_template = \BOB_ROOT_DIR . 'skel/data.template';
		$wp_out = \dirname(\BOB_ROOT_DIR) . '/wp/lib/blobfolio/wp/bm/mime/aliases.php';
		$wp_template = \BOB_ROOT_DIR . 'skel/wp.template';

		\file_put_contents("{$bin_out}extensions_by_mime.json", \json_encode(static::$exm));
		\file_put_contents("{$bin_out}mimes_by_extension.json", \json_encode(static::$mxe));

		// And a combined version.
		$content = array(
			'extensions'=>array(),
			'mimes'=>array(),
		);
		foreach (static::$exm as $k=>$v) {
			$content['mimes'][$k] = $v['ext'];
		}
		foreach (static::$mxe as $k=>$v) {
			$tmp = $v['mime'];

			// Pre-calculate "loose" MIMEs.
			foreach ($tmp as $v) {
				$loose = \blobfolio\mimes\mimes::get_loose_mimes($v);
				foreach ($loose as $v2) {
					if (! \in_array($v2, $tmp, true)) {
						$tmp[] = $v2;
					}
				}
			}

			$content['extensions'][$k] = $tmp;
		}
		\file_put_contents("{$bin_out}blob-mimes.json", \json_encode($content));

		// Export the main data used by this library.
		log::print('Exporting library data…');

		$content = \file_get_contents($data_template);

		$replacements = array(
			'%GENERATED%'=>\date('Y-m-d H:i:s'),
			'%EXTENSIONS_BY_MIME%'=>format::array_to_php(static::$exm, 2),
			'%MIMES_BY_EXTENSION%'=>format::array_to_php(static::$mxe, 2),
		);

		$content = \str_replace(
			\array_keys($replacements),
			\array_values($replacements),
			$content
		);
		\file_put_contents($data_out, $content);

		// Also generate aliases for the WordPress plugin.
		log::print('Exporting WP plugin data…');

		$content = \file_get_contents($wp_template);

		// WordPress needs simpler data.
		$data = array();
		foreach (static::$mxe as $k=>$v) {
			$data[$k] = $v['mime'];
			\sort($data[$k]);
		}

		$content = \str_replace(
			'%MIMES_BY_EXTENSION%',
			format::array_to_php($data, 2),
			$content
		);
		\file_put_contents($wp_out, $content);

		// Finally, report how many MIMEs and Extensions we found!
		$count = \number_format(\count(static::$mxe), 0, '.', ',');
		log::total($count, 'file extensions');
		$count = \number_format(\count(static::$exm), 0, '.', ',');
		log::total($count, 'MIME types');
	}

	// ----------------------------------------------------------------- end release



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
		$xml = \simplexml_load_string($xml);
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
					if (('type' === $k) && (false === \strpos($v, '/x-tika'))) {
						$mimes[\strval($v)] = true;
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
							$v = \ltrim($v, '.*');
							if (\preg_match('/^[\da-z]+[\da-z\-\_]*[\da-z]+$/', $v)) {
								$exts[] = $v;
							}
						}
					}
				}
			}

			// We have something!
			if (\count($exts) && \count($mimes)) {
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
		if (! $ext || ! $mime || ! $source || ('unknown' === $ext)) {
			return;
		}

		// Consensus building.
		if (! $alias) {
			if (! isset(static::$consensus_ext[$ext])) {
				static::$consensus_ext[$ext] = array();
			}
			if (! isset(static::$consensus_ext[$ext][$mime])) {
				static::$consensus_ext[$ext][$mime] = 0;
			}
			++static::$consensus_ext[$ext][$mime];
		}

		// MIMEs by extension.
		if (! isset(static::$mxe[$ext])) {
			static::$mxe[$ext] = static::MIMES_BY_EXTENSION;
			static::$mxe[$ext]['ext'] = $ext;
		}

		// Add to MIME list.
		if (! \in_array($mime, static::$mxe[$ext]['mime'], true)) {
			static::$mxe[$ext]['mime'][] = $mime;
		}

		// Add to alias list.
		if ($alias && ! \in_array($mime, static::$mxe[$ext]['alias'], true)) {
			static::$mxe[$ext]['alias'][] = $mime;
		}

		// Note the source.
		if (! \in_array($source, static::$mxe[$ext]['source'], true)) {
			static::$mxe[$ext]['source'][] = $source;
		}

		// Extensions by MIME.
		if (! isset(static::$exm[$mime])) {
			static::$exm[$mime] = static::EXTENSIONS_BY_MIME;
			static::$exm[$mime]['mime'] = $mime;
		}

		// Add extension.
		if (! \in_array($ext, static::$exm[$mime]['ext'], true)) {
			static::$exm[$mime]['ext'][] = $ext;
		}

		// Add source.
		if (! \in_array($source, static::$exm[$mime]['source'], true)) {
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
			if (0 === \strpos($mime, $k)) {
				return $v;
			}
		}

		return false;
	}

	// ----------------------------------------------------------------- end helpers
}
