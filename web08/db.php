<?php
$host = "127.0.0.1";
$db = "web08";
$user = "root";
$pass = "";

$conn = new mysqli($host,$user,$pass, $db);

if($conn->connect_error){
    die("連接失敗". $conn->connect_error);
}
?>