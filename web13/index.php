<?php
include 'db.php';

$sql = "SELECT * FROM `comp`";
$result = $conn->query($sql)


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
            <div class="logo">LOGO</div>
            <div class="title">title</div>
            <a href="login.php">管理</a>
            <a href="prod_d.php">查詢產品</a>
        </div>
        <h2>照片</h2>
        <div class="img">
            <div class="pic">1</div>
            <div class="pic">2</div>
            <div class="pic">3</div>
        </div>
        <h2>公司</h2>
        <table>
            <tr>
                <td>公司名稱</td>
                <td>公司地址</td>
                <td>公司電話</td>
                <td>公司電子郵件</td>
                <td>公司負責人</td>             
            </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <?php if($row['status'] == 'Invalid'):?>
                    <th>該公司被隱藏</th>
                <?php else:?>
                    <tr>
                        <th><a href="root.php?comp_id=<?= $row['id']?>" style="text-decoration: none;"><?= $row['name']?></a></th>
                        <th><?= $row['address']?></th>
                        <th><?= $row['phone']?></th>
                        <th><?= $row['email']?></th>
                        <th><?= $row['owner_name']?></th>                   
                    </tr>
                <?php endif;?>
            <?php endwhile;?>
        </table>        
    </div>
    <div class="footer">
        <p>sdfsdf</p>
    </div>
</body>
</html>