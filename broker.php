#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');
require_once('functions.inc');

function doLogin($username, $password)
{
    // lookup username in database
    // check password
    $result = false;
    if (!isset($username) || !isset($password)) {
        echo "invalid input";
        return false;
    }
    $db        = mysqli_connect('3.16.251.147', 'emile', 'Password7!', 'authtest');
    $pass_hash = hash('sha512', $password);
    $s         = sprintf("SELECT * FROM users WHERE username='%s' AND passhash='%s'", mysqli_real_escape_string($db, $username), mysqli_real_escape_string($db, $pass_hash));
    $t = mysqli_query($db, $s) or die(mysqli_error($db));
    $num       = mysqli_num_rows($t);
    $file      = __FILE__ . PHP_EOL;
    $pathArray = explode("/", $file);
    if ($num > 0) {
        echo "success";
        $result = true;
    } else {
        echo "FAILURE";
        $result = false;
    }
    

    //echo log_api_data(1, 'test', '1990-10-10', true);
    echo "checkpoint";
    return $result;
    
    //$login = new loginDB();
    //return $login->validateLogin($username,$password);
    //return false if not valid
}

function doCampaign($title, $username, $subreddit)
{
    // lookup username in database
    // check password
    $result = false;
    if (!isset($username) || !isset($title) || !isset($subreddit)) {
        echo "invalid input";
        return false;
    }
    $db        = mysqli_connect('3.16.251.147', 'emile', 'Password7!', 'authtest');
    $s         = sprintf("SELECT * FROM campaign WHERE title='%s'", mysqli_real_escape_string($db, $title));
    $t = mysqli_query($db, $s) or die(mysqli_error($db));
    $num       = mysqli_num_rows($t);
    $file      = __FILE__ . PHP_EOL;
    $pathArray = explode("/", $file);
    if ($num > 0) {
        echo "Title already saved";
        $result = true;
    } else {
        $s3 = sprintf("INSERT INTO campaign (title, subreddit, user) VALUES ('%s', '%s', '%s')", mysqli_real_escape_string($db, $title), mysqli_real_escape_string($db, $subreddit), mysqli_real_escape_string($db, $username));
        $t2 = mysqli_query($db, $s3) or die(mysqli_error($db));
	echo "Saving Title";
        $result = true;
    }
    

    //echo log_api_data(1, 'test', '1990-10-10', true);
    echo "checkpoint";
    return $result;
    
    //$login = new loginDB();
    //return $login->validateLogin($username,$password);
    //return false if not valid
}

function doEmail($username, $email,$yn)
{
    // lookup username in database
    // check email
    $result = false;
    if (!isset($username) || !isset($email)) {
        echo "invalid input";
        return false;
    }
    $db        = mysqli_connect('3.16.251.147', 'emile', 'Password7!', 'authtest');
    $s         = sprintf("SELECT * FROM email_notif WHERE username='%s' AND email ='%s'", mysqli_real_escape_string($db, $username), mysqli_real_escape_string($db, $email));
    $t = mysqli_query($db, $s) or die(mysqli_error($db));
    $num       = mysqli_num_rows($t);
    $file      = __FILE__ . PHP_EOL;
    $pathArray = explode("/", $file);

	if ($num == 0) {
	   $s2 = sprintf("INSERT INTO email_notif (username, email, notif) VALUES ('%s', '%s', '1')", mysqli_real_escape_string($db, $username), mysqli_real_escape_string($db, $email));
           $t2 = mysqli_query($db, $s2) or die(mysqli_error($db));
           echo "email_added with default notif to true.";        
           $result = true;
	} else {
	    if ($yn == 'yes'){
	       //update notif to 1
	        $s2 = sprintf("UPDATE TABLE email_notif SET notif = 1 WHERE username = '%s'", mysqli_real_escape_string($db, $username));
                $t2 = mysqli_query($db, $s2) or die(mysqli_error($db));
                echo "email_updated";
	    } else if ($yn=='no') {
                $s2 = sprintf("UPDATE TABLE email_notif SET notif = 0 WHERE username = '%s'", mysqli_real_escape_string($db, $username));
                $t2 = mysqli_query($db, $s2) or die(mysqli_error($db));
                echo "email_updated";
            }	
	   $result = true;
	}
    


    //echo log_api_data(1, 'test', '1990-10-10', true);
    echo "checkpoint";
    return $result;
    
    //$login = new loginDB();
    //return $login->validateLogin($username,$password);
    //return false if not valid
}

function doRegister($username, $password)
{
    $result = false;

    if (!isset($username) || !isset($password)) {
        echo "invalid input";
        return false;
    }
    
    
    $db        = mysqli_connect('3.16.251.147', 'emile', 'Password7!', 'authtest');
    $pass_hash = hash('sha512', $password);
    $s         = sprintf("SELECT * FROM users WHERE username='%s' AND passhash='%s'", mysqli_real_escape_string($db, $username), mysqli_real_escape_string($db, $pass_hash));
    $t = mysqli_query($db, $s) or die(mysqli_error($db));
    $num       = mysqli_num_rows($t);
    $file      = __FILE_ . PHP_EOL;
    $pathArray = explode("/", $file);
    if ($num == 0) {
        $s2 = sprintf("INSERT INTO users (username, passhash) VALUES ('%s', '%s')", mysqli_real_escape_string($db, $username), mysqli_real_escape_string($db, $pass_hash));
        $t2 = mysqli_query($db, $s2) or die(mysqli_error($db));
        echo "register";
        $result = true;
    } else {
        echo "Already registered";
        $result = false;
    }
  
    $result_str = ($result) ? 'true' : 'false';
    $data   = 'signup: user=' . $username . ', pass=' . $password . ', result=' . $result_str . ' ' . PHP_EOL;

    $file = 'messages.txt';
    $handle = fopen($file, 'a') or die('Cannot open file: ' . $file);
    echo fwrite($handle, $data);
    fclose($handle);

    return $result;
}

function requestProcessor($request)
{
    echo "received request" . PHP_EOL;
    var_dump($request);
    
    
    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }
    switch ($request['type']) {
        case "login":
            return doLogin($request['username'], $request['password']);
        case "register":
            return doRegister($request['username'], $request['password']);
	case "emailnotif":
	    return doEmail($request['user'], $request['email'], $request['yn']);
	case "table_campaign":
	    return doCampaign($request['title'], $request['subreddit'], $request['user']);
        case "validate_session":
            return doValidate($request['sessionId']);
    }
    //log_message($request);
    return array(
        "returnCode" => '0',
        'message' => "Server received request and processed"
    );
}

$server = new rabbitMQServer("RabbitMQ.ini", "testServer");
echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
exit();
?>
