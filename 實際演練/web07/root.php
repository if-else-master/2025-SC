<?php
$comp_id = $_GET['comp_id'];
include 'db.php';


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
            <a href="index.php">返回</a>
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
                </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <?php if($row['status'] == "Invalid"):?>
                    <th>該產品被隱藏</th>
                <?php else:?>                    
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
                    </tr>
                <?php endif;?>
            <?php endwhile;?>
        </table>
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>