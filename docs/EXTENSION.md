# blob-mimes

Search by file extension.



##### Table of Contents

 * [Example](#example)
 * Methods
  * [is_valid()](#is_valid)
  * [has_mime()](#has_extension)
  * [get()](#get)
  * [get_extension()](#get_extension)
  * [get_mime()](#get_mime)
  * [get_mimes()](#get_mimes)
  * [get_sources()](#get_sources)



## Example

```php
$ext = new \blobmimes\extension('xls');
$info = $ext->get();
print_r($info);
/*
Array
(
    [extension] => xls
    [mime] => application/vnd.ms-excel
    [mimes] => Array
        (
            [0] => application/msexcel
            [1] => application/vnd.ms-excel
            [2] => application/x-msexcel
            [3] => zz-application/zz-winassoc-xls
        )

    [sources] => Array
        (
            [0] => Apache
            [1] => Nginx
            [2] => WordPress
            [3] => freedesktop.org
        )

)
*/
```



## is_valid()

Lets you know whether any extension information could be found.

#### Arguments

N/A

#### Return

`TRUE` or `FALSE`



## has_mime()

Says whether or not a given extension is registered with this MIME type.

#### Arguments

 * (*string*) MIME type
 * (*bool*) (*optional*) Loose match. If `TRUE`, will check for both `general/specific` and `general/x-specific` variants. Default: `FALSE`

#### Return

`TRUE` or `FALSE`



## get()

Return the information associated with a given file extension.

#### Arguments

N/A

#### Return

Returns an array containing the following:

 * (*string*) `extension`: the file extension
 * (*string*) `mime`: the primary MIME type entry
 * (*array*) `mimes`: all MIME type entries
 * (*array*) `sources`: the MIME database sources containing an entry for this file extension



## get_extension()

Return the file extension, in case you forgot?

#### Arguments

N/A

#### Return

The file extension as a (*string*), just like the corresponding key in the response from `get()`.



## get_mime()

Return the primary MIME type.

#### Arguments

N/A

#### Return

The MIME type as a (*string*), just like the corresponding key in the response from `get()`.



## get_mimes()

A given file extension may have more than one MIME type association, either because of history or developer disagreement. This returns them all.

#### Arguments

N/A

#### Return

An (*array*) of MIME types, just like the corresponding key in the response from `get()`.



## get_sources()

Return the data sources used to define this file extension.

#### Arguments

N/A

#### Return

An (*array*) of MIME database sources, just like the corresponding key in the response from `get()`.