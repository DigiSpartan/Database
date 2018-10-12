#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');
$log = "messages.log";

function log_message($request){
	$log_file = fopen($log,"a+");
	$message = "";
	$keys = array_keys($array);
	$key = current($keys);
	$written = "";

	$s = "ID: " . $log_id . "\n";
	fwrite($log_file, $s);
	$written .= $s;
	foreach ($request as &$value) {
		$s = "\t" . $key . ": " . $value . "\n";
		fwrite($log_file, $s);
		$key = next($keys);
		$written .= $s;
	}
	fwrite($log_id, "END " . $log_id . ":\n");
	log_id++;
	return $written;
}

?>
