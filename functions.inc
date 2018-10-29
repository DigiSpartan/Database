#!/usr/bin/php
<?php
require_once('rabbitMQLib.inc');
$log = "messages.log";

function log_message($request)
{
    $log_file = fopen($log, "a+");
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
    $log_id++;

    return $written;
}

function log_api_data($rid, $rUser, $accountCreationDate, $verified) {
    $db = mysqli_connect('localhost', 'emile', 'Password7!', 'authtest');
    
    $rUser = mysqli_real_escape_string($db, $rUser);

    $s = "INSERT INTO reddit_data VALUES('".$rid."', '".$rUser."', CAST('". $accountCreationDate ."' AS DATE), '".$verified."');";
    $t = (mysqli_query($db, $s) or die(mysqli_errno($db)));   

    return $t;
}
?>
