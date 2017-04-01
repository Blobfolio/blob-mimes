# blob-mimes

[![Build Status](https://travis-ci.org/Blobfolio/blob-mimes.svg?branch=master)](https://travis-ci.org/Blobfolio/blob-mimes)

The secret to blob-mime's success is using a more complete source database than other software typically integrates. This database is built from multiple sources, as it is too much to ask that any single definitive source should exist:

 * [IANA](https://www.iana.org/assignments/media-types)
 * [Apache](https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types)
 * [Nginx](http://hg.nginx.org/nginx/raw-file/default/conf/mime.types)
 * [freedesktop.org](https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in)
 * [Apache Tika](https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml)

The source data may update at any time. This repository will be updated periodically, but if you need more up-to-date sources, you can recompile the database files locally by running the included build script:

```php
//run it in the terminal of your choice:
php build/build.php
```

This will regenerate the `\blobfolio\mimes\data` class, which contains the MIME and file extension information.

It will also output the same information in JSON format for use under other platforms.
 * `build/extensions_by_mime.json`
 * `build/mimes_by_extension.json`
 