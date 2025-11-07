<?php
$servername = "localhost";
$username = "tre_n3";
$password = "+Abc123!tre";
$dbname = "tre_n3";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
	die("Verbindung fehlgeschlagen: ". $conn->connect_error);
}

$conn->set_charset("utf8");

?>