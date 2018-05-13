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


use \blobfolio\dev\mimes;

require(__DIR__ . '/lib/vendor/autoload.php');

// Set up some quick constants, namely for path awareness.
define('BOB_BUILD_DIR', __DIR__ . '/');
define('BOB_ROOT_DIR', dirname(BOB_BUILD_DIR) . '/');

// Compilation is as easy as calling this method!
mimes::compile();

// We're done!
exit(0);
