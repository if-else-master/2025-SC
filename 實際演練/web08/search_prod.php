<?php
include 'db.php';
$comp_name = null;

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['search_gtin'])){
    $gtin = $_POST['gtin'];   

    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $comp_id = $row['comp_id'];

    $prod_img = $row['image_path'];
    $prod_gtin = $row['gtin'];
    $prod_des = $row['des'];
    $prod_des_en = $row['des_en'];

    $sql2 = "SELECT * FROM `comp` WHERE id = $comp_id";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $comp_name = $row2['name'];


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
            <a href="prod_d.php">返回</a>
        </div>        
        <h2>查詢產品資訊</h2>

        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="gtin">GTIN碼</label>
                    <textarea name="gtin" required></textarea>
                </div>
                <button type="submit" class="button" name="search_gtin">查詢</button>                
            </form>                        
        </div>

        <?= $comp_name?>
        <img src="<?= $prod_img ?>" style="max-width:100px">
        <?= $prod_gtin?><br>
        <?= $prod_des?>
        
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>