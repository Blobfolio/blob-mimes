# Lord of the Files

A WordPress plugin that expands file-related security around the upload process.



##### Table of Contents

1. [Features](#features)
2. [Installation](#installation)
3. [Reference](#reference)
   * [Filters](#filters)
   * [Functions](#functions)
   * [Misc](#misc)
4. [License](#license)



## Features

WordPress relies mostly on name-based validation when deciding whether or not to allow a particular file, leaving the door open for various kinds of attacks.

Lord of the Files adds to this content-based validation and sanitizing, making sure that files are what they say they are and safe for inclusion on your site.

The main features include:
 * Robust *real* filetype detection;
 * Full MIME alias mapping;
 * SVG sanitization (if SVG uploads have been whitelisted);
 * File upload debugger;
 * Fixes issues related to [#40175](https://core.trac.wordpress.org/ticket/40175) that have been present since WordPress `4.7.1`.



## Installation

Just head on over to [https://wordpress.org/plugins/blob-mimes/](#https://wordpress.org/plugins/blob-mimes/) and install it like any other plugin.



## Reference

No code changes are necessary to benefit from the security improvements, however there are under-the-hood goodies developers can hook into to modify the behaviors if so desired.



### Filters



#### blobmimes_check_application_octet_stream

Whether or not a "real" MIME type of `application/octet-stream` should be subjected to further scrutiny. By default, such responses are given a soft pass because, while it can be a legitimate type, it is also a system's equivalent of a shrug.

##### Arguments

| Type | Name   | Description         |
| ---- | ------ | ------------------- |
| bool | $check | Run further checks. |

##### Return

Return `TRUE` to run additional checks, `FALSE` to just let it slide. 



#### blobmimes_check_mime_alias

Override the plugin's determination about whether a given file extension and MIME type belong together.

##### Arguments

| Type   | Name   | Description            |
| ------ | ------ | ---------------------- |
| bool   | $match | The plugin's response. |
| string | $ext   | File extension.        |
| string | $mime  | MIME type.             |

##### Return

Return `TRUE` if they're a match, `FALSE` if not. 



#### blobmimes_check_real_filetype

Override the plugin's determination about a file's "real" MIME type.

##### Arguments

| Type   | Name      | Description            |
| ------ | --------- | ---------------------- |
| array  | $result   | The plugin's response. |
| string | $file     | File path.             |
| string | $filename | File name.             |
| array  | $mimes    | Allowed MIMEs.         |

##### Return

Return an array containing the true extension and mime type, like below. If no match is found, set the values to `FALSE`.

```
// A JPEG.
array(
    'ext'=>'jpg',
    'type'=>'image/jpeg'
)

// Something bad.
array(
    'ext'=>false,
    'type'=>false
)
```



#### blobmimes_get_mime_aliases

Override the list of MIME aliases matching a particular file extension.

##### Arguments

| Type       | Name    | Description                      |
| ---------- | ------- | -------------------------------- |
| array/bool | $result | An array of MIME types or false. |
| string     | $ext    | File extension.                  |

##### Return

Return an array of MIME types for the extension or `FALSE`.



#### blobmimes_svg_allowed_attributes

Override the list of attributes (`width`, `height`, `id`, etc.) allowed to exist within an SVG.

##### Arguments

| Type  | Name        | Description             |
| ----- | ----------- | ----------------------- |
| array | $attributes | An array of attributes. |

##### Return

Return an array of attributes.



#### blobmimes_svg_allowed_domains

The list of external hosts an SVG is allowed to link to is restricted to the following hosts by default:
 * (your site)
 * creativecommons.org
 * inkscape.org
 * sodipodi.sourceforge.net
 * w3.org

This filter allows that list to be altered.

##### Arguments

| Type  | Name     | Description          |
| ----- | ---------| -------------------- |
| array | $domains | An array of domains. |

##### Return

Return an array of domains. 



#### blobmimes_svg_allowed_protocols

Override the list of protocols IRI attributes can connect to. By default this is only `http(s)`.

##### Arguments

| Type  | Name       | Description            |
| ----- | ---------- | ---------------------- |
| array | $protocols | An array of protocols. |

##### Return

Return an array of protocols. 



#### blobmimes_svg_allowed_tags

Override the list of tags (`circle`, `defs`, `g`, etc.) allowed to exist within an SVG.

##### Arguments

| Type  | Name  | Description       |
| ----- | ----- | ----------------- |
| array | $tags | An array of tags. |

##### Return

Return an array of tags.



#### blobmimes_svg_doctype

Override the DOCTYPE assigned to an SVG, which by default is:
```
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
```

##### Arguments

| Type  | Name      | Description  |
| ----- | --------- | ------------ |
| string | $doctype | The DOCTYPE. |

##### Return

Return the DOCTYPE as a string.



#### blobmimes_svg_pre_sanitize

Alter the SVG code before the sanitization routines have gotten started.

##### Arguments

| Type  | Name  | Description              |
| ----- | ----- | ------------------------ |
| string | $svg | SVG code or a file path. |

##### Return

Return a string containing your choice of SVG code or file path or `FALSE` to abort.



#### blobmimes_svg_post_sanitize

Alter the SVG after sanitization has otherwise completed.

##### Arguments

| Type  | Name  | Description              |
| ----- | ----- | ------------------------ |
| string | $svg | SVG code or a file path. |

##### Return

Return a string containing your choice of SVG code or file path or `FALSE` to abort.


### Functions

All functions are under the namespace `blobfolio\wp\bm`. To keep the reference tidy, relative function names are shown.

In practice, you can use either of the following:

```
use blobfolio\wp\bm;
$foo = mime::get_aliases($bar);

// OR...

$foo = blobfolio\wp\bm\mime::get_aliases($bar);
```



#### mime::check_alias()

Check whether a file extension and MIME type belong together. This function supports all known aliases for each registered file type, including historical, vernacular, and wrong/buggy responses returned by various platforms.

##### Arguments

| Type   | Name  | Description     |
| ------ | ----- | --------------- |
| string | $ext  | File extension. |
| string | $mime | MIME type.      |

##### Return

Returns `TRUE` or `FALSE`.



#### mime::check_real_filetype()

This is a more robust version of [wp_check_filetype()](https://developer.wordpress.org/reference/functions/wp_check_filetype/) that, in addition to name-based validation, attempts to examine the file for content-based indicators of its true type.

##### Arguments

| Type       | Name      | Description                                |
| ---------- | --------- | ------------------------------------------ |
| string     | $file     | File path.                                 |
| string     | $filename | File name.                                 |
| array/null | *$mimes*  | Allowed MIME types. Default based on user. |

##### Return

The return structure is the same as `wp_check_filetype()` (an array with `ext` and `type` keys; if the file isn't valid the values will be `FALSE`).

Note: the `ext` returned may not match what you put in depending on the file's true nature.



#### mime::get_aliases()

Get the main aliases for a file extension. The `mime::check_alias()` function contains additional logic to help determine matches, so in most cases it is better to use that.

##### Arguments

| Type   | Name | Description     |
| ------ | ---- | --------------- |
| string | $ext | File extension. |

##### Return

Returns an array of MIME aliases or `FALSE`.



#### mime::update_filename_extension()

A simple function to help rename a file by giving it a different extension.

##### Arguments

| Type   | Name      | Description    |
| ------ | --------- | -------------- |
| string | $filename | File name.     |
| string | $ext      | New extension. |

##### Return

Returns the new filename as a string.



#### svg::get_dimensions()

Try to get the width and height from an SVG file.

##### Arguments

| Type   | Name | Description            |
| ------ | ---- | ---------------------- |
| string | $svg | SVG code or file path. |

##### Return

Returns an array containing `width` and `height` keys (values as floats), or `FALSE`.



#### svg::sanitize()

Sanitize an SVG by applying the following:
 * Removal of comments (`<!-- -->` and `/* */` varieties);
 * Removal of XML, PHP, ASP;
 * Removal of Javascript tags, attributes, and values;
 * Sanitizing of CSS `url(...)` rules;
 * Namespace and IRI sanitizing via protocol and host (both extensible);
 * Extensible tag whitelist;
 * Extensible attribute whitelist;
 * Miscellaneous formatting repairs;
 * Whitespace collapsing;

DOMDocument is not required, however when installed will provide more robust namespace handling, malformatting recovery, etc.

This is applied automatically when uploading an SVG through the Media Library (assuming SVG file types have been whitelisted via the `upload_mimes` filter).

##### Arguments

| Type   | Name     | Description                           |
| ------ | -------- | ------------------------------------- |
| string | $svg     | SVG code or file path.                |
| bool   | $headers | Prepend with XML and DOCTYPE headers. |

##### Return

Returns the sanitized SVG code as a string or `FALSE` if it could not be salvaged.



### Miscellaneous

The full list of MIME aliases by file extension can be accessed via `mime\aliases::$data`.



## License

Copyright © 2017 [Blobfolio, LLC](https://blobfolio.com) &lt;hello@blobfolio.com&gt;

This work is free. You can redistribute it and/or modify it under the terms of the Do What The Fuck You Want To Public License, Version 2.

    DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
    Version 2, December 2004
    
    Copyright (C) 2017 Sam Hocevar <sam@hocevar.net>
    
    Everyone is permitted to copy and distribute verbatim or modified
    copies of this license document, and changing it is allowed as long
    as the name is changed.
    
    DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
    TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
    
    0. You just DO WHAT THE FUCK YOU WANT TO.