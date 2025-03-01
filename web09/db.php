<?php
$h = "127.0.0.1";
$db = "web09";
$user = "root";
$pass = "";

$conn = new mysqli($h,$user,$pass,$db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>