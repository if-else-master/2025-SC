<?php
include 'db.php';

$gtin_list = [];
$prod = [];
$statusn = 0;
$die = [];

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['search_gtin'])){
    $gtin = $_POST['gtin'];   

    $clean = preg_replace("/\s+/",'',$gtin);
    $gtin_list = str_split($clean,13);
    

    foreach($gtin_list as $gtins){
        if(strlen($gtins)!=13){
            die('無效的GTIN碼');
        }
        $sql = "SELECT * FROM `prod` WHERE gtin = $gtins";
        $result = $conn->query($sql);
        
        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                $prod[] = $row;
    
                if($row['status'] == "Invalid"){
                    $statusn += 1;
                }
            }
        }else{
            $statusn += 1;
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
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
    <div class="cont">
        <div class="header">
            <div class="logo"><img src="img/web08logo.png" alt="LOGO" style="max-width: 80px"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="index.php">返回</a>
            <a href="search_prod.php">查詢產品</a>
        </div>        
        <h2>批次查詢</h2>

        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="gtin">GTIN碼</label>
                    <textarea name="gtin" required></textarea>
                </div>
                <button type="submit" class="button" name="search_gtin">查詢</button>                
            </form>            
            <?php if ($statusn == 0 && !empty($gtin)):?>
                <h2>all valid</h2>            
            <?php endif;?>            
            <?php foreach($prod as $prods):?>              
                <h3><?= $prods['gtin']?>:<?= $prods['status']?></h3>                
            <?php endforeach;?>
            <?php foreach($die as $dies):?>              
                <h3><?= $dies?>:Invalid</h3>                
            <?php endforeach;?>
        </div>
        
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>