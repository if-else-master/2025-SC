<?php
include 'db.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search'])){
    $gtin = $_POST['gtin'];

    $sql = "SELECT * FROM `prod` WHERE gtin = $gtin";
    $result = $conn->query($sql);

    $row = $result->fetch_assoc();
    
    $comp_id = $row['comp_id'];
    $image = $row['image_path'];
    $prodgtin = $row['gtin'];
    $des = $row['des'];
    $des_en = $row['des_en'];

    $compsql = "SELECT * FROM `comp` WHERE id = $comp_id";
    $compresult = $conn->query($compsql);

    $row2 = $compresult->fetch_assoc();
    $comp_name = $row['name'];
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
            <div class="logo"><img src="img/web07logo.png" alt="logo" style="max-width: 80px;"></div>
            <div class="title">臺灣人工智慧公會管理系統</div>
            <a href="prod_d.php">返回</a>            
        </div>
        <h2>產品查詢</h2>
        <div class="in">
            <form action="#" method="POST">
                <div class="put">
                    <label for="gtin">GTIN碼：</label>
                    <input type="text" name="gtin" required>
                </div>                
                <button type="submit" class="button" name="search">查詢</button>
            </form>            
        </div>
        <table>
            <?php if(!empty($gtin)):?>
                <tr>
                    <td><?= $comp_name?></td>
                </tr>
                <tr>
                    <th>
                        <img src="<?= $image?>" style="max-width:100px;">
                    </th>
                </tr>
                <tr>
                    <td><?= $prodgtin?></td>
                </tr>
                <tr>
                    <th><?= $des?></th>
                </tr>
            <?php endif;?>
        </table>

        <div class="padding"></div>
        <footer>
            <p>sdfsdfsdfsdf</p>
        </footer>
    </div>
</body>
</html>