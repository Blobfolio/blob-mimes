<?php

//test with wordpress?
//require_once(dirname(dirname(dirname(__FILE__))) . '/wp-load.php');
//bootstrap
require_once(dirname(dirname(__FILE__)) . '/vendor/autoload.php');
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
$mime = new \blobmimes\mime('audio/mp3');
debug_stdout($mime->get());



//by extension
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE EXTENSION', true);
$ext = new \blobmimes\extension('xls');
debug_stdout($ext->get());



//by local file
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (CORRECTLY NAMED)', true);
$file = new \blobmimes\file('files/sky.jpg');
debug_stdout($file->get());



//by local file
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (INCORRECTLY NAMED)', true);
$file = new \blobmimes\file('files/sky.png');
debug_stdout($file->get());



//by made up file name
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (NAME ONLY)', true);
$file = new \blobmimes\file('pkcs12-test-keystore.tar.gz');
debug_stdout($file->get());



//by remote file
debug_stdout();
debug_stdout();
debug_stdout('LOOKUP BY FILE (URL)', true);
$file = new \blobmimes\file('https://upload.wikimedia.org/wikipedia/commons/7/76/Mozilla_Firefox_logo_2013.svg');
debug_stdout($file->get());
?>