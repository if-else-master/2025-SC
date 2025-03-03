<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == "admin" && $password == "abcd1234"){
        header("Location:comp.php");
        exit();
    }else{
        echo "帳號密碼錯誤";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-cont">
        <h2>登入</h2>
        <form action="#" method="post">
            <div class="form-group">
                <label for="username">帳號</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">帳號</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="login-button">登入</button>
            <div class="padding"></div>
            <a href="index.html"><button class="button1" type="button">回首頁</button></a>
        </form>
    </div>
    
</body>
</html>