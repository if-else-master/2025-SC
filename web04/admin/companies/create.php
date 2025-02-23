<?php
require_once '../auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增會員公司</title>    
</head>
<body>
    <div>
        <h1>新增會員公司</h1>
        <form action="store.php" method="POST" class="form">
            <div>
                <label for="name">公司名稱：</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="name">地址：</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div>
                <label for="name">電話：</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div>
                <label for="name">電子郵件</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div>
                <label for="name">擁有者姓名：</label>
                <input type="text" id="owner_name" name="owner_name" required>
            </div>
            <div class="form-actions">
                <button type="submit">儲存</button>
                <a href="index.php">返回</a>
            </div>
        </form>
    </div>
</body>
</html>