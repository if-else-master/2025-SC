<?php

session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit();
}
include 'db.php';
$comp_id = $_GET['comp_id'];

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    if($_GET['action']=="Invalid"){
        $new_status="Invalid";
    }elseif($_GET['action']=="valid"){
        $new_status="valid";
    }

    $sql = "UPDATE prod SET status = ? where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$new_status,$id);
    $stmt->execute();

}


$sql = "SELECT * FROM `prod` where comp_id = $comp_id";
$result = $conn->query($sql);


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="cont">
        <div class="header">
            <div class="logo"><img src="img/web07logo.png" alt="LOGO" style="width: 85px;"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="comp.php">返回</a>
            <a href="add_prod.php?comp_id=<?=$comp_id?>">新增產品</a>            
        </div>        
        <h2>產品資訊</h2>
        <table>
            <tr>
                <td>產品名稱(中文)</td>
                <td>產品名稱(英文)</td>
                <td>GTIN</td>
                <td>產品描述(中文)</td>
                <td>產品描述(英文)</td>
                <td>產品圖片</td>
                <td>狀態</td>
                <td>操作</td>
                <td>修改</td>
            </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <tr>
                    <th><?= $row['name']?></th>
                    <th><?= $row['name_en']?></th>
                    <th><?= $row['gtin']?></th>
                    <th><?= $row['des']?></th>
                    <th><?= $row['des_en']?></th>
                    <th>
                        <img src="<?= $row['image_path']?>" style="max-width:100px;">
                    </th>
                    <th><?= $row['status']?></th>
                    <th>
                        <?php if($row['status']=="valid"):?>
                            <a href="prod.php?comp_id=<?=$row['comp_id']?>&action=Invalid&id=<?=$row['id']?>" style="color:red; text-decoration: none;">隱藏</a>
                        <?php elseif($row['status']=="Invalid"):?>
                            <a href="prod.php?comp_id=<?=$row['comp_id']?>&action=valid&id=<?=$row['id']?>" style="color:blue; text-decoration: none;">公開</a>
                        <?php endif;?>
                    </th>
                    <th>8</th>
                </tr>
            <?php endwhile;?>
        </table>

        <div class="padding"></div>

        <footer>
            <p>sdfsdfgsdfsdfsd</p>
        </footer>
    </div>
</body>
</html>