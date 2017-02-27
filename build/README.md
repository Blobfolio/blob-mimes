# blob-mimes

[![Build Status](https://travis-ci.org/Blobfolio/blob-mimes.svg?branch=master)](https://travis-ci.org/Blobfolio/blob-mimes)

The secret to blob-mime's success is using a more complete source database than other software typically integrates. This database is built from multiple sources, as it is too much to ask that any single definitive source should exist:

 * [IANA](https://www.iana.org/assignments/media-types)
 * [Apache](https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types)
 * [Nginx](http://hg.nginx.org/nginx/raw-file/default/conf/mime.types)
 * [freedesktop.org](https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in)

The source data can update at any time. This repository will be updated monthly, but if you need more up-to-date sources, you can recompile the database files locally by running the included build script:

```php
//run it in the terminal of your choice:
php build/build.php
```

This will store local copies of the data it retrieves in `build/src`.

The combined data will be output to two different JSON files:
 * `build/extensions_by_mime.json`
 * `build/mimes_by_extension.json`

After a build has completed, copy these two files to the main `lib/blobfolio/mimes` directory.