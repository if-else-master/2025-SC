<?php
include 'db.php';
$comp_name = null;
$gtins = null;
$image_path = null;
$des = null;
$des_en = null;


if(!isset($_SESSION['lan'])){
    $_SESSION['lan']=0;
}

if(isset($_GET['action'])){
    if($_GET['action']=="en"){
        $_SESSION['lan'] = 1;
    }elseif($_GET['action'] == "ch"){
        $_SESSION['lan'] = 0;
    }
}


if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['search_gtin'])){
    $gtin = $_POST['gtin'];   

    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $comp_id = $row['comp_id'];
    $gtins = $row['gtin'];
    $image_path = $row['image_path'];
    $des = $row['des'];
    

    $sql2 = "SELECT * FROM `comp` WHERE id = $comp_id";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $comp_name = $row2['name'];
}


if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['ch'])){
    $gtin = $_POST['gtin'];   

    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $comp_id = $row['comp_id'];
    $gtins = $row['gtin'];
    $image_path = $row['image_path'];
    $des = $row['des'];
    

    $sql2 = "SELECT * FROM `comp` WHERE id = $comp_id";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $comp_name = $row2['name'];
}

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['en'])){
    $gtin = $_POST['gtin'];   

    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    $comp_id = $row['comp_id'];
    $gtins = $row['gtin'];
    $image_path = $row['image_path'];
    $des = $row['des_en'];
    

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
                    <textarea name="gtin" required>
                        <?php if(isset($gtin)):?>
                            <?= $gtin?>
                        <?php endif;?>
                    </textarea>
                </div>
                <button type="submit" class="button" name="search_gtin">查詢</button>  
                <button type="submit" class="button" name="ch">中文</button>           
                <button type="submit" class="button" name="en">英文</button>                             
            </form>                        
        </div>

        <?= $comp_name?>
        <h3><?= $gtins?></h3>
        <img src="<?= $image_path?>" style="max-width:100px;">
        <h3 class="des"><?= $des?></h3>        
        
        
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>