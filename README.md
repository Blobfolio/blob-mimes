# blob-mimes

A comprehensive MIME and file extension tool for PHP. Finally!



##### Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
2. [Installation](#installation)
3. [Reference](#reference)
   * [check_ext_and_mime()](#check_ext_and_mime)
   * [finfo()](#finfo)
   * [get_extension()](#get_extension)
   * [get_extensions()](#get_extensions)
   * [get_mime()](#get_mime)
   * [get_mimes()](#get_mimes)
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



## Requirements

blob-mimes requires PHP 7+ with the following modules:

 * BCMath
 * DOM
 * Fileinfo
 * Filter
 * JSON
 * MBString
 * SimpleXML

UTF-8 is used for all string encoding. This could create conflicts on environments using something else.

The build script additionally requires cURL.



## Installation

Install via Composer:
```bash
composer require "blobfolio/blob-mimes:dev-master"
```



## Reference

### check_ext_and_mime()

Verify that a file extension and MIME type belong together. Because technology is always evolving and the MIME standard is always changing, this will consider `some/thing` and `some/x-thing` equivalent.

#### Arguments

 * (*string*) File extension
 * (*string*) MIME type
 * (*bool*) (*optional*) Soft pass. If `TRUE`, the check will return `TRUE` if it lacks information about either the extension or MIME type.

#### Returns

Returns `TRUE` or `FALSE`.

#### Example

```php
$foo = blobfolio\mimes\mimes::check_ext_and_mime('jpeg', 'image/jpeg'); //TRUE
$foo = blobfolio\mimes\mimes::check_ext_and_mime('jpeg', 'image/gif'); //FALSE
```



### finfo()

Pull path and type information for a file, using its name and/or content. If it is determined that the file is incorrectly named, alternative names with the correct file extension(s) are suggested.

#### Arguments

 * (*string*) Path
 * (*string*) (*optional*) Nice name. If provided, the nice name will be treated as the filename. This can be useful if passing a temporary upload, for example. Default: `NULL`

#### Returns

Returns all file information that can be derived according to the format below.

#### Example

```php
print_r(blobfolio\mimes\mimes::finfo('../wp/img/blobfolio.svg'));
/*
array(
    [dirname] => /var/www/blob-common/wp/img
    [basename] => blobfolio.svg
    [extension] => svg
    [filename] => blobfolio
    [path] => /var/www/blob-common/wp/img/blobfolio.svg
    [mime] => image/svg+xml
    [suggested_filename] => array()
)
*/
```



### get_extension()

Retrieve information about a file extension.

#### Arguments

 * (*string*) File extension

#### Returns

Returns the information or `FALSE`.

```php
print_r(blobfolio\mimes\mimes::get_extension('jpeg'));
/*
array(
    [ext] => jpeg
    [mime] => array(
        0 => image/jpeg
        1 => image/pjpeg
    )
    [source] => array(
        0 => Apache
        1 => Nginx
        2 => freedesktop.org
    )
    [alias] => array(
        ] => image/pjpeg
    )
    [primary] => image/jpeg
)
*/
```



### get_extensions()

Retrieve information about all known file extensions.

#### Arguments

N/A

#### Returns

Returns a MIME database oganized by extension.



### get_mime()

Retrieve information about a MIME type.

#### Arguments

 * (*string*) MIME type

#### Returns

Returns the information or `FALSE`.

#### Example

```php
print_r(blobfolio\mimes\mimes::get_mime('image/jpeg'));
/*
array(
    [mime] => image/jpeg
    [ext] => array(
        0 => jpeg
        1 => jpg
        2 => jpe
    )
    [source] => array(
        0 => Apache
        1 => Nginx
        2 => freedesktop.org
    )
)
*/
```



### get_mimes()

Retrieve information about all known MIME types.

#### Arguments

N/A

#### Returns

Returns a MIME database oganized by type.



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
