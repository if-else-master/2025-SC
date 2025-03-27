<?php
include 'db.php';

session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit();
}
$comp_id = $_GET['comp_id'];
$id = $_GET['id'];


if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['updateimg'])){    

    $image_path = "uploads/aa.png";
    $upload_dir = "uploads/";

    if(!empty($_FILES['image']['name'])){
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

   $sql = "UPDATE prod SET image_path = ? where id = ?";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param('si',$image_path,$id);
   $stmt->execute();


    header("Location:prod.php?comp_id=$comp_id");
}

$sql = "SELECT * FROM `prod` WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


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
        <h2>修改產品圖片</h2>

        <div class="in">
            <form action="#" method="POST" enctype="multipart/form-data">                
                <div class="put">
                    <label for="image">修改產品圖片</label>
                    <input type="file" name="image" accept="image/*">
                    <img src="<?=$row['image_path']?>" style="max-width:100px;">
                </div>
                <button type="submit" class="button" name="updateimg">修改</button>
            </form>
        </div>
        
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>