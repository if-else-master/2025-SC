<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['name'];
    $password = $_POST['password'];

    if($name == "admin" && $password == "abcd1234"){
        header('Location:comp.php');
    }else{
        echo'帳號密碼錯誤';
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="cont">
        <div class="header">
            <div class="logo">LOGO</div>
            <div class="title">管理</div>
            <a href="index.php">回首頁</a>            
        </div>
        <h2>登入</h2>
        <div class="in">
            <form action="#" method="POST">
                <div class="box">
                    <label for="name">帳號</label>
                    <input type="text" name="name" required>
                </div>
                <div class="box">
                    <label for="password">密碼</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="button">登入</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>sdfsdf</p>
    </div>
</body>
</html>
</body>
</html>