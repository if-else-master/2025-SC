<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理系統</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="admin-container">
        <h1>歡迎使用管理系統</h1>
        <nav>
            <ul>
                <li><a href="companies/">會員公司管理</a></li>
                <li><a href="products/">產品管理</a></li>
                <li><a href="logout.php">登出</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>