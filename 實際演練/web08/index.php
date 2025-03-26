<?php
include 'db.php';


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
            <div class="logo"><img src="img/web08logo.png" alt="LOGO" style="max-width: 80px"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="login.php">管理</a>
            <a href="prod_d.php">批次驗證</a>
        </div>
        <div class="padding"></div>
        <div class="img">
            <div class="pic"><img src="img/123.jpg" style="max-width: 800px;"></div>
        </div>
        <h2>公司資訊</h2>
        <table>
            <tr>
                <td>公司名稱</td>
                <td>公司地址</td>
                <td>公司電話</td>
                <td>公司電子郵件</td>
                <td>公司擁有者</td>
                <td>狀態</td>               
            </tr>
            <?php while($row = $result->fetch_assoc()):?>
                          
                
                    <tr>
                    <?php if($row['status']=='Invalid'):?>      
                        <th>該公司被隱藏</th>    
                    <?php else:?>
                        <th><a href="root.php?comp_id=<?=$row['id']?>"><?= $row['name']?></a></th>
                        <th><?= $row['address']?></th>
                        <th><?= $row['phone']?></th>
                        <th><?= $row['email']?></th>
                        <th><?= $row['owner_name']?></th>
                        <th><?= $row['status']?></th>     
                        <?php endif;?>               
                    </tr>                
            <?php endwhile;?>
        </table>
        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>