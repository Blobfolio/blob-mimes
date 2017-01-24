<?php

//bootstrap
require_once(dirname(dirname(__FILE__)) . '/lib/vendor/autoload.php');
header('Content-type: text/plain');



//-------------------------------------------------
// Debug Stdout
//
// @param line
// @param dividers
// @return n/a
function debug_stdout($line='', bool $dividers=false) {
	if ($dividers) {
		echo str_repeat('-', 50) . "\n";
	}

	if (is_string($line)) {
		echo "$line\n";
	}
	else {
		print_r($line);
	}

	if ($dividers) {
		echo str_repeat('-', 50) . "\n";
	}
}



//by mime
debug_stdout('LOOKUP BY MIME TYPE', true);
$mime = \blobfolio\mimes\mimes::get_mime('audio/mp3');
debug_stdout($mime);



//by extension
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE EXTENSION', true);
$ext = \blobfolio\mimes\mimes::get_extension('xls');
debug_stdout($ext);



//by local file
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (CORRECTLY NAMED)', true);
$file = \blobfolio\mimes\mimes::finfo('files/sky.jpg');
debug_stdout($file);



//by local file
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (INCORRECTLY NAMED)', true);
$file = \blobfolio\mimes\mimes::finfo('files/sky.png');
debug_stdout($file);



//by made up file name
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (NAME ONLY)', true);
$file = \blobfolio\mimes\mimes::finfo('pkcs12-test-keystore.tar.gz');
debug_stdout($file);



//by remote file
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (URL)', true);
$file = \blobfolio\mimes\mimes::finfo('https://upload.wikimedia.org/wikipedia/commons/7/76/Mozilla_Firefox_logo_2013.svg');
debug_stdout($file);
?>