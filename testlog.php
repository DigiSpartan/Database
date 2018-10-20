#!/usr/bin/php
<?php
$file = 'messages.txt';
$handle = fopen($file, 'a') or die('Cannot open file: ' .$file);
$data = 'this is text';
fwrite($handle, $data);
$data = '\nnew text';
fwrite($handle, $data);
fclose($handle);
?>
