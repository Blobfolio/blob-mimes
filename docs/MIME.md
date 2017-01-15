# blob-mimes

Search by MIME type.



##### Table of Contents

 * [Example](#example)
 * Methods
  * [is_valid()](#is_valid)
  * [has_extension()](#has_extension)
  * [get()][#get]
  * [get_mime()](#get_mime)
  * [get_extensions()](#get_extensions)
  * [get_sources()](#get_sources)



## Example

```php
$mime = new \blobmimes\mime('audio/mp3');
$info = $mime->get();
print_r($info);
/*
Array
(
    [mime] => audio/mp3
    [extensions] => Array
        (
            [0] => mp3
            [1] => mpga
        )

    [sources] => Array
        (
            [0] => freedesktop.org
        )

)
*/
```



## is_valid()

Lets you know whether any MIME information could be found.

#### Arguments

N/A

#### Return

`TRUE` or `FALSE`



## has_extension()

Says whether or not a given extension is registered with this MIME type.

#### Arguments

 * (*string*) Extension

#### Return

`TRUE` or `FALSE`



## get()

Return the information associated with a given MIME type.

#### Arguments

N/A

#### Return

Returns an array containing the following:

 * (*string*) `mime`: the MIME type
 * (*array*) `extensions`: registered file extensions
 * (*array*) `sources`: the MIME database sources containing an entry for this MIME type



## get_mime()

Return the MIME type, in case you forgot?

#### Arguments

N/A

#### Return

The MIME type as a (*string*), just like the corresponding key in the response from `get()`.



## get_extensions()

Return the file extensions registered with the MIME type.

#### Arguments

N/A

#### Return

An (*array*) of file extensions, just like the corresponding key in the response from `get()`.



## get_sources()

Return the data sources used to define this MIME type.

#### Arguments

N/A

#### Return

An (*array*) of MIME database sources, just like the corresponding key in the response from `get()`.