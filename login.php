<?php
require_once 'php/db.php';

$result = $conn->query("SELECT * FROM Benutzer");

while ($row = $result->fetch_assoc())
{
	echo $row['username']." ".$row['name']."<br>";
}

?>