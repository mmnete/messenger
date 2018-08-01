<?php
$db = new mysqli("localhost","root","","messanger_db");


$username = stripslashes(htmlspecialchars($_GET['username']));
$message = stripslashes(htmlspecialchars($_GET['message']));

if($username == "" || $message == "")
{
  die();
}

$result = $db->prepare("INSERT INTO messages VALUES ('',?,?)");
$result->bind_param("ss",$username, $message);
$result->execute();

?>
