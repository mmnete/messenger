<?php
$db = new mysqli("localhost","root","","messanger_db");

$id = $_GET['id'];

$result = $db->prepare("DELETE FROM messages WHERE id = ?");
$result->bind_param("s",$id);
$result->execute();



?>
