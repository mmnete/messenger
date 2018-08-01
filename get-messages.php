<?php
$db = new mysqli("localhost","root","","messanger_db");


$result = $db->prepare("SELECT * FROM messages");
$result->execute();

$result = $result->get_result();

while ($r = $result->fetch_row())
{
  echo $r[1];
  echo "\\";
  echo $r[2];
  echo "\\";
  echo $r[0];
  echo "\n";
}

?>
