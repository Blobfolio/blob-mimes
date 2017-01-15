# blob-mimes

A comprehensive MIME and file extension tool for PHP. Finally!



##### Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
2. [Installation](#installation)
3. Reference
  * [By MIME](https://github.com/Blobfolio/blob-mimes/blob/master/docs/MIME.md)
  * [By Extension](https://github.com/Blobfolio/blob-mimes/blob/master/docs/EXTENSION.md)
  * [By File](https://github.com/Blobfolio/blob-mimes/blob/master/docs/FILE.md)
  * [Build Sources](https://github.com/Blobfolio/blob-mimes/blob/master/docs/BUILD.md)
4. [License](#license)



## Features

It should be simple, but MIME types and file extensions are wildly inconsistent. Attempting to derive and/or correct that information when, e.g. verifying a file upload, is a nightmare in any programming language.

blob-mimes is a PHP library with a comprehensive MIME/ext database and simple helpers to access that information in a variety of ways:

 * Pull MIME information based on a file name or extension;
 * Pull file extensions for a given MIME type;
 * Pull MIME information from a local file and verify its extension matches the actual content;

The database is compiled from the following sources:

 * [IANA](https://www.iana.org/assignments/media-types)
 * [Apache](https://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types)
 * [Nginx](http://hg.nginx.org/nginx/raw-file/default/conf/mime.types)
 * [freedesktop.org](https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in)
 * [WordPress](https://developer.wordpress.org/reference/functions/wp_get_mime_types/) *at runtime, if present



## Requirements

At a bare minimum you need to be running PHP v5.3+ with the `php-json` module.

Composer requires PHP v5.3.2.

Optional modules include:
 
 * `php-mbstring`: multi-byte support;
 * `fileinfo`: derive "true" file type from contents;
 * `iconv`: UTF-8 support if you have not installed via Composer or are not running WordPress

The standalone build script requires a Unix environment (e.g. Linux, OS X, BSD...) and PHP CLI v7.0+ with the following modules:

 * `php-mbstring`
 * `php-simplexml`
 * `php-curl`



## Installation

Composer is the easiest:
```bash
composer require "blobfolio/blob-mimes:dev-master"
```

Otherwise, download the `src` directory and include all the PHP scripts in your application. If your project has an autoloader, point the `blobmimes` namespace to `src/blobmimes`.



## License

Copyright © 2017  Blobfolio, LLC  (email: hello@blobfolio.com)

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
