<?php
include 'db.php';
$list_gtin = [];
$prod = [];
$status = 0;

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search'])){
    $gtin = $_POST['gtin'];
    $clean = preg_replace('/\s+/','',$gtin);
    $list_gtin = str_split($clean,13);

    foreach($list_gtin as $gtin){
        $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()){
            $prod[] = $row;

            if($row['status']=='Invalid'){
                $status+=1;
            }
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
            <div class="logo"><img src="img/web07logo.png" alt="logo" style="max-width: 80px;"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="search.php">產品查詢</a>
            <a href="index.php">返回</a>            
        </div>
        <h2>批次驗證</h2>
        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="gtin">GTIN碼：</label>
                    <textarea name="gtin" required></textarea>
                </div>                
                <button type="submit" class="button" name="search">查詢</button>
            </form>  
            <?php if($status==0):?>
                <h3>All Valid</h3>
            <?php endif;?>
            <h2>
                <?php foreach($prod as $prods):?>
                    <h2><?= $prods['gtin']?>:<?= $prods['status']?></h2>
                <?php endforeach;?>
            </h2>            
        </div>

        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>