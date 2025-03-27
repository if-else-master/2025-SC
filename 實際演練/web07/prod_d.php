<?php
include 'db.php';
$gtin_list = [];
$comp_name = null;
$status_list = [];
$Invalid = 0;
$die = [];

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['search'])){
    $gtin = $_POST['gtin'];
    $clean = preg_replace("/\s+/",'',$gtin);
    $gtin_list = str_split($clean,13);

    foreach($gtin_list as $gtins){
        $sql = "SELECT * FROM `prod` WHERE gtin = $gtins";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $status_list[] = $row;
                
                if($row['status']=="Invalid"){
                    $Invalid +=1;
                }                
            }
        }else{
            $Invalid +=1;
            $die[] = $gtins;
        }
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
            <a href="search.php">查詢產品</a>  
        </div>
        <h2>批次驗證</h2>
        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="gtin">GTIN碼</label>
                    <textarea name="gtin" required></textarea>
                </div>                
                <button type="submit" class="button" name="search">驗證</button>
            </form>
        </div>               

        <?php if($Invalid == 0 && !empty($gtin)):?>
            <h3>All Valid</h3>
        <?php endif;?>
        <?php foreach($status_list as $statuss):?>
            <h3><?= $statuss['gtin']?>:<?= $statuss['status']?></h3>
        <?php endforeach;?>
        <?php foreach($die as $dies):?>
            <h3><?= $dies?>:Invalid</h3>
        <?php endforeach;?>

        <div class="padding"></div>

        <footer>
            <p>sdfsdfgsdfsdfsd</p>
        </footer>
    </div>
</body>
</html>