<?php
include 'db.php';

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    if($_GET['action'] == 'Invalid'){
        $new_status = "Invalid";
    }elseif($_GET['action'] == 'valid'){
        $new_status = "valid";
    }

    $sql = "UPDATE `comp` SET status = ? where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si',$new_status,$id);
    $stmt->execute();


    $sql2 = "UPDATE `prod` SET status = ? where comp_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('si',$new_status,$id);
    $stmt2->execute();

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
            <div class="logo"><img src="img/web07logo.png" alt="logo" style="max-width: 80px;"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="login.php">登出</a>
            <a href="add_comp.php">新增公司</a>
        </div>
            <h2>公司資訊</h2>        
        <table>            
                <tr>
                    <td>公司名稱</td>
                    <td>公司地址</td>
                    <td>公司電話</td>
                    <td>公司郵件</td>
                    <td>公司負責人</td>
                    <td>狀態</td>
                    <td>操作</td>
                </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <tr>
                    <th><a href="prod.php?comp_id=<?= $row['id']?>&comp_status=<?=$row['status']?>"><?= $row['name']?></a></th>
                    <th><?= $row['address']?></th>
                    <th><?= $row['phone']?></th>
                    <th><?= $row['email']?></th>
                    <th><?= $row['owner_name']?></th>
                    <th><?= $row['status']?></th>
                    <th>
                        <?php if($row['status'] == "valid"):?>
                            <a href="?action=Invalid&id=<?=$row['id']?>">隱藏</a>
                        <?php elseif($row['status'] == "Invalid"):?>
                            <a href="?action=valid&id=<?=$row['id']?>">公開</a>
                        <?php endif;?>
                    </th>
                </tr>
            <?php endwhile;?>
        </table>
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>