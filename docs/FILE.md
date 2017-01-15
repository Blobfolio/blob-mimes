# blob-mimes

Search by file path. This can be a full path to a local file, a partial path (e.g. just the name), or a remote web address.

If a full path to a local file is provided and PHP is built with the `fileinfo` extension, it will attempt to derive the MIME type ["magically"](https://en.wikipedia.org/wiki/File_format#Magic_number) by analyzing the file content. (Can't really trust users to correctly name their image files...)

If the magic analysis doesn't work or if `fileinfo` is unavailable, the file extension will be implicitly trusted.



##### Table of Contents

 * [Example](#example)
 * Methods
  * [is_valid()](#is_valid)
  * [has_incorrect_name()](#has_incorrect_name)
  * [get()](#get)
  * [get_file()](#get_file)



## Example

```php
//pretend kangaroo.jpg is actually a PNG
$file = new \blobmimes\extension('/path/to/kangaroo.jpg');
$info = $file->get();
print_r($info);
/*
Array
(
    [dirname] => /home/raspberrypencil.com/httpdocs/mime-test/test/files
    [basename] => kangaroo.jpg
    [extension] => jpg
    [filename] => kangaroo
    [path] => /home/raspberrypencil.com/httpdocs/mime-test/test/files/kangaroo.jpg
    [mime] => image/png
    [suggested] => Array
        (
            [0] => kangaroo.png
        )

)
*/
```



## is_valid()

Lets you know whether the file was at all valid.

#### Arguments

 * (*bool*) (*optional*) Strict. If `TRUE`, a file with an incorrect extension will be considered invalid. Default: `FALSE`

#### Return

`TRUE` or `FALSE`



## has_incorrect_name()

If the file was able to be analyzed "magically" for its MIME type based on content, this will say whether or not it was appropriately named.

#### Arguments

N/A

#### Return

`TRUE` or `FALSE`



## get()

Return the information associated with a given file extension.

#### Arguments

N/A

#### Return

Returns an array containing the following:

 * (*string*) `dirname`: the directory portion of the path
 * (*string*) `basename`: the basename portion of the path (file name + extension)
 * (*string*) `extension`: the file extension
 * (*string*) `filename`: the file name (w/o extension)
 * (*string*) `mime`: the best candidate MIME type for this file
 * (*array*) `suggested`: if the file contents reveal a different MIME type than the file extension, corrected names are offered.



## get_file()

This is an alias of `get()`.