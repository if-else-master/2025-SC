<?php
$host = '127.0.0.1';
$dbname = 'gtin_system';
$username = 'root';
$password = '';


try {
    $pdo = new PDO(
        dsn: "mysql:host=$host;dbname=$dbname;sharset=utf8mb4",
        username: $username,
        password: $password,
        options:[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,    
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
}catch(PDOException $e){
    die("連接失敗". $e->getMessage());
}

?>