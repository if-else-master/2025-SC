<?php
$host = '127.0.0.1';
$db = 'web07';
$user = 'root';
$pass = '';

$conn = new mysqli(hostname:$host,username:$user,password:$pass,database:$db);
if($conn->connect_error){
    die('連接失敗'. $conn->connect_error);
}

?>