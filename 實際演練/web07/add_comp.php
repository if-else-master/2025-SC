<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit();
}

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['add_comp'])){
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO `comp`(`name`, `address`, `phone`, `email`, `owner_name`) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss',$name,$address,$phone,$email,$owner_name);
    $stmt->execute();

    header('Location:comp.php');
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
            <a href="comp.php">返回</a>         
        </div>
        <h2>新增公司</h2>
        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="name">公司名稱</label>
                    <input type="text" name="name" required>
                </div>
                <div class="put">
                    <label for="address">公司地址</label>
                    <input type="text" name="address" required>
                </div>
                <div class="put">
                    <label for="phone">公司電話號碼</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="put">
                    <label for="email">公司電子郵件地址</label>
                    <input type="email" name="email" required>
                </div>
                <div class="put">
                    <label for="owner_name">擁有者姓名</label>
                    <input type="text" name="owner_name" required>
                </div>

                
                <button type="submit" class="button" name="add_comp">新增</button>
            </form>
        </div>

        <div class="padding"></div>

        <footer>
            <p>sdfsdfgsdfsdfsd</p>
        </footer>
    </div>
</body>
</html>