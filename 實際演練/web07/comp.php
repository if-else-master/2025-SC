<?php
include 'db.php';
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:login.php');
    exit();
}

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    if($_GET['action']=="Invalid"){
        $new_status="Invalid";
    }elseif($_GET['action']=="valid"){
        $new_status="valid";
    }

    $sql = "UPDATE comp SET status = ? where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$new_status,$id);
    $stmt->execute();

}

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    if($_GET['action']=="Invalid"){
        $new_status="Invalid";
    }elseif($_GET['action']=="valid"){
        $new_status="valid";
    }

    $sql = "UPDATE prod SET status = ? where comp_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$new_status,$id);
    $stmt->execute();

}


$sql = "SELECT * FROM `comp`";
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
            <a href="logout.php">登出</a>
            <a href="add_comp.php">新增公司</a>            
        </div>        
        <h2>公司資訊</h2>
        <table>
            <tr>
                <td>公司名稱</td>
                <td>公司地址</td>
                <td>公司電話號碼</td>
                <td>公司電子郵件地址</td>
                <td>擁有者姓名</td>
                <td>狀態</td>
                <td>操作</td>
                <td>修改</td>
            </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <tr>
                    <th><a href="prod.php?comp_id=<?= $row['id']?>" style="color:blue; text-decoration: none;"><?= $row['name']?></a></th>
                    <th><?= $row['address']?></th>
                    <th><?= $row['phone']?></th>
                    <th><?= $row['email']?></th>
                    <th><?= $row['owner_name']?></th>
                    <th><?= $row['status']?></th>
                    <th>
                        <?php if($row['status']=="valid"):?>
                            <a href="?action=Invalid&id=<?=$row['id']?>" style="color:red; text-decoration: none;">隱藏</a>
                        <?php elseif($row['status']=="Invalid"):?>
                            <a href="?action=valid&id=<?=$row['id']?>" style="color:blue; text-decoration: none;">公開</a>
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