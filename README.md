# blob-mimes

A comprehensive MIME and file extension tool for PHP. Finally!

[![Build Status](https://travis-ci.org/Blobfolio/blob-mimes.svg?branch=master)](https://travis-ci.org/Blobfolio/blob-mimes)

&nbsp;

## Table of Contents

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

&nbsp;

## Features

It should be simple, but MIME types and file extensions are wildly inconsistent. Attempting to derive and/or correct that information when, e.g. verifying a file upload, is a nightmare in any programming language.

blob-mimes is a PHP library with a comprehensive MIME/ext database and simple helpers to access that information in a variety of ways:

 * Pull MIME information based on a file name or extension;
 * Pull file extensions for a given MIME type;
 * Pull MIME information from a local file and verify its extension matches the actual content;

The database is compiled from the following sources:

 * [IANA](https://www.iana.org/assignments/media-types)
 * [Apache](https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types)
 * [Nginx](http://hg.nginx.org/nginx/raw-file/default/conf/mime.types)
 * [freedesktop.org](https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in)
 * [Apache Tika](https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml)

&nbsp;

## Requirements

blob-mimes requires PHP 5.6+ with the following modules:

 * BCMath
 * DOM
 * Fileinfo
 * Filter
 * JSON
 * MBString
 * SimpleXML

UTF-8 is used for all string encoding. This could create conflicts on environments using something else.

The build script additionally requires cURL.

&nbsp;

## Installation

Install via Composer:
```bash
composer require "blobfolio/blob-mimes:dev-master"
```

&nbsp;

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

&nbsp;

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

&nbsp;

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

&nbsp;

### get_extensions()

Retrieve information about all known file extensions.

#### Arguments

N/A

#### Returns

Returns a MIME database organized by extension.

&nbsp;

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

&nbsp;

### get_mimes()

Retrieve information about all known MIME types.

#### Arguments

N/A

#### Returns

Returns a MIME database organized by type.

&nbsp;

## License

Copyright © 2018 [Blobfolio, LLC](https://blobfolio.com) &lt;hello@blobfolio.com&gt;

This work is free. You can redistribute it and/or modify it under the terms of the Do What The Fuck You Want To Public License, Version 2.

    DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
    Version 2, December 2004
    
    Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>
    
    Everyone is permitted to copy and distribute verbatim or modified
    copies of this license document, and changing it is allowed as long
    as the name is changed.
    
    DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
    TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
    
    0. You just DO WHAT THE FUCK YOU WANT TO.

### Donations

<table>
  <tbody>
    <tr>
      <td width="200"><img src="https://blobfolio.com/wp-content/themes/b3/svg/btc-github.svg" width="200" height="200" alt="Bitcoin QR" /></td>
      <td width="450">If you have found this work useful and would like to contribute financially, Bitcoin tips are always welcome!<br /><br /><strong>1PQhurwP2mcM8rHynYMzzs4KSKpBbVz5is</strong></td>
    </tr>
  </tbody>
</table>
