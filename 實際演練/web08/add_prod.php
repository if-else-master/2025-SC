<?php
include 'db.php';

session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit();
}

$comp_id = $_GET['comp_id'];
if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['add_prod'])){
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $des = $_POST['des'];
    $des_en = $_POST['des_en'];

    $image_path = "uploads/aa.png";
    $upload_dir = "uploads/";

    if(!empty($_FILES['image']['name'])){
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $sql = "INSERT INTO `prod`(`comp_id`, `name`, `name_en`, `gtin`, `des`, `des_en`, `image_path`) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssss',$comp_id,$name,$name_en,$gtin,$des,$des_en,$image_path);
    $stmt->execute();

    header("Location:prod.php?comp_id=$comp_id");
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
    <div class="cont">
        <div class="header">
            <div class="logo"><img src="img/web08logo.png" alt="LOGO" style="max-width: 80px"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="prod.php?comp_id=<?=$comp_id?>">返回</a>
        </div>        
        <h2>新增產品</h2>

        <div class="in">
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="put">
                    <label for="name">產品名稱</label>
                    <input type="text" name="name" required>
                </div>
                <div class="put">
                    <label for="name_en">產品英文</label>
                    <input type="text" name="name_en" required>
                </div>
                <div class="put">
                    <label for="gtin">GTIN碼</label>
                    <input type="text" name="gtin" required>
                </div>
                <div class="put">
                    <label for="des">產品說明</label>
                    <textarea name="des" required></textarea>
                </div>
                <div class="put">
                    <label for="des_en">產品英文說明</label>
                    <textarea name="des_en" required></textarea>
                </div>
                <div class="put">
                    <label for="image">產品圖片</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <button type="submit" class="button" name="add_prod">新增</button>
            </form>
        </div>
        
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>