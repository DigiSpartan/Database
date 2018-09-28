#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');

function doLogin($username,$password)
{
    // lookup username in databas
	// check password
   $db = mysqli_connect('localhost', 'emile', 'Password7!', 'authtest');
   $s = "SELECT * FROM users WHERE username = '$username' AND password='$password'";
   $t = mysqli_query($db, $s) or die (mysqli_error($db));
   $num = mysqli_num_rows($t);
   $file=__FILE_.PHP_EOL;
   $pathArray = explode("/",$file);
   if ($num > 0) {
      echo "success";
      return true;
   }
   else {
     echo "FAILURE";
     return false;
   }

   //$login = new loginDB();
    //return $login->validateLogin($username,$password);
    //return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");
echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

