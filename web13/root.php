<?php
include 'db.php';
$comp_id = $_GET['comp_id'];

$sql = "SELECT * FROM `prod` where comp_id = $comp_id";
$result = $conn->query($sql)

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
            <div class="logo">LOGO</div>
            <div class="title">產品管理</div>
            <a href="index.php">返回</a>            
        </div>
        <h2>產品</h2>
        <table>
            <tr>
                <td>產品名稱</td>
                <td>產品英文名稱</td>
                <td>gtin碼</td>
                <td>產品說明</td>
                <td>產品英文說明</td>
                <td>圖片</td>               
            </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <?php if($row['status'] == 'Invalid'):?>
                    <th>該產品被隱藏</th>
                <?php else:?>
                    <tr>
                        <th><?= $row['name']?></th>
                        <th><?= $row['name_en']?></th>
                        <th><?= $row['gtin']?></th>
                        <th><?= $row['des']?></th>
                        <th><?= $row['des_en']?></th>
                        <th>
                            <img src="<?= $row['image_path']?>" style="max-width:100px;">
                        </th>                   
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
</body>
</html>