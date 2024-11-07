<?php
//Edited by batnet
$host = "localhost";
$username = "root";
$password = "";
$database = "qnbnet";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
