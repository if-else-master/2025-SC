<?php
session_start();


if(!isset($_SESSION['rand']) || isset($_POST['rand'])){
    $_SESSION['rand'] = rand(1000,9999);
    
}


if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['logined'])){
    $name = $_POST['name'];
    $password = $_POST['password'];
    $rands = $_POST['rands'];
   

    if($name == "admin" && $password == "abcd1234" && $rands == $_SESSION['rand']){
        header('Location:comp.php');
        $_SESSION['user_id'] = $name;
        exit();
    }elseif($rands != $_SESSION['rand']){
        echo'驗證碼錯誤';
        echo $_SESSION['rand'];
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
    <div class="cont">
        <div class="header">
            <div class="logo"><img src="img/web07logo.png" alt="LOGO" style="width: 85px;"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="index.php">返回</a>         
        </div>
        <h2>登入</h2>
        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="name">帳號</label>
                    <input type="text" name="name" required>
                </div>
                <div class="put">
                    <label for="password">密碼</label>
                    <input type="password" name="password" required>
                </div>                                     
                <div class="put">
                    <label for="rands">驗證碼</label>
                    <input type="text" name="rands">
                </div>
                <button type="submit" class="button" name="logined">登入</button>                
            </form>

            <form action="#" method="POST">
                <button type="submit" class="button" name="rand">刷新</button>
            </form>
            <?= $_SESSION['rand']?>
        </div>               
        <div class="padding"></div>

        <footer>
            <p>sdfsdfgsdfsdfsd</p>
        </footer>
    </div>
</body>
</html>