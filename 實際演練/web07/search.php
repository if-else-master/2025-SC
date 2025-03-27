<?php
include 'db.php';
$image_path = null;
$gtins = null;
$des = null;
$comp_name = null;

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['search'])){
    $gtin = $_POST['gtin'];
    
    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $comp_id = $row['comp_id'];

    $image_path = $row['image_path'];
    $gtins = $row['gtin'];
    $des = $row['des'];

    $sql2 = "SELECT * FROM `comp` WHERE id = $comp_id";
    $result_comp = $conn->query($sql2);

    $row_comp = $result_comp->fetch_assoc();
    $comp_name = $row_comp['name'];

    
}

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['ch'])){
    $gtin = $_POST['gtin'];
    
    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $comp_id = $row['comp_id'];

    $image_path = $row['image_path'];
    $gtins = $row['gtin'];
    $des = $row['des'];

    $sql2 = "SELECT * FROM `comp` WHERE id = $comp_id";
    $result_comp = $conn->query($sql2);

    $row_comp = $result_comp->fetch_assoc();
    $comp_name = $row_comp['name'];

    
}

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['en'])){
    $gtin = $_POST['gtin'];
    
    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $comp_id = $row['comp_id'];

    $image_path = $row['image_path'];
    $gtins = $row['gtin'];
    $des = $row['des_en'];

    $sql2 = "SELECT * FROM `comp` WHERE id = $comp_id";
    $result_comp = $conn->query($sql2);

    $row_comp = $result_comp->fetch_assoc();
    $comp_name = $row_comp['name'];

    
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
            <a href="prod_d.php">返回</a>       
        </div>
        <h2>查詢產品</h2>
        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="gtin">GTIN碼</label>
                    <textarea name="gtin" required><?= $gtins?></textarea>
                </div>                
                <button type="submit" class="button" name="search">查詢</button>
                <div class="lena">
                    <button type="submit" class="button_lan" name="ch">中文</button>
                    <button type="submit" class="button_lan" name="en">英文</button>
                </div>
            </form>
        </div>               
        <h3><?= $comp_name?></h3>
        <img src="<?= $image_path?>" style="max-width:100px;">
        <h3><?= $gtins?></h3>
        <h3><?= $des?></h3>

        

        <div class="padding"></div>

        <footer>
            <p>sdfsdfgsdfsdfsd</p>
        </footer>
    </div>
</body>
</html>