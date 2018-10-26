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

function log_api_data($rUser, $rPassword, $rid, $accountCreationDate, $verified) {
    $db = mysqli_connect('localhost', 'emile', 'Password7!', 'authtest');
    
    $rUser = mysqli_real_escape_string($rUser);
    $rPassword = mysqli_real_escape_string($rPassword);
    $rPassword = hash('sha512', $rPassword);
    $rid = mysqli_real_escape_string($rid);
    $accountCreationDate = mysqli_real_escape_string($accountCreationDate);
    $verified = mysqli_real_escape_string($verified);

    $s = "INSERT INTO 'reddit_data' VALUES('$rUser', '$rPassword', '$rid', '$accountCreationDate', '$verified');";
    $t = mysqli_query($db, $s) or die(mysqli_errno($db));   

    $result = false;
    if ($db->query($sql) === TRUE) {
        $result = true;
    }

    return $result;
}
?>
