<?php
$comp_id = $_GET['comp_id'];
$comp_status = $_GET['comp_status'];
include 'db.php';

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    if($_GET['action'] == 'Invalid'){
        $new_status = "Invalid";
    }elseif($_GET['action'] == 'valid'){
        $new_status = "valid";
    }

    $sql = "UPDATE `prod` SET status = ? where id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si',$new_status,$id);
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
            <div class="logo"><img src="img/web07logo.png" alt="logo" style="max-width: 80px;"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="comp.php">返回</a>
            <a href="add_prod.php?comp_id=<?= $comp_id?>">新增產品</a>
           
        </div>
            <h2>公司資訊</h2>        
        <table>            
                <tr>
                    <td>產品名稱</td>
                    <td>產品英文</td>
                    <td>GTIN碼</td>
                    <td>產品介紹</td>
                    <td>產品英文介紹</td>
                    <td>介紹圖片</td>
                    <td>狀態</td>
                    <td>操作</td>
                </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <tr>
                    <th><?= $row['name']?></th>
                    <th><?= $row['name_en']?></th>
                    <th><?= $row['gtin']?></th>
                    <th><?= $row['des']?></th>                    
                    <th><?= $row['des_en']?></th>
                    <th>
                        <img src="<?=$row['image_path']?>" style="max-width:100px;">
                    </th>
                    <th><?=$row['status']?></th>                    
                    <th>
                        <?php if($comp_status == "Invalid"):?>
                            該公司被隱藏
                        <?php else:?>
                            <?php if($row['status'] == "valid"):?>
                                <a href="prod.php?comp_id=<?= $comp_id?>&action=Invalid&id=<?=$row['id']?>">隱藏</a>
                            <?php elseif($row['status'] == "Invalid"):?>
                                <a href="prod.php?comp_id=<?= $comp_id?>&action=valid&id=<?=$row['id']?>">公開</a>
                            <?php endif;?>
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