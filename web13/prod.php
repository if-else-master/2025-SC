<?php
include 'db.php';
$comp_id = $_GET['comp_id'];

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_prod'])){
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $des = $_POST['des'];
    $des_en = $_POST['des_en'];
    
    if(strlen($gtin) != 13){
        die('無效的gtin');
    }

    $image_path = 'uploads/aa.png';
    $upload_dir = 'uploads/';

    if(!empty($_FILES['image']['name'])){
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'],$image_path);
    }
    

    $sql = "INSERT INTO `prod`(`comp_id`, `name`, `name_en`, `gtin`, `des`, `des_en`, `image_path`) VALUES (?,?,?,?,?,?,?)";
    $stut = $conn->prepare($sql);
    $stut->bind_param('issssss',$comp_id,$name,$name_en,$gtin,$des,$des_en,$image_path);
    $stut->execute();
}

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    if($_GET['action']=='Invalid'){
        $new_status = 'Invalid';
    }elseif($_GET['action']=='valid'){
        $new_status = 'valid';
    }

    $sql = "UPDATE prod SET status = ? where id = ?";
    $stut = $conn->prepare($sql);
    $stut->bind_param('si',$new_status,$id);
    $stut->execute();
}


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
            <a href="comp.php">返回</a>            
        </div>
        <h2>新增產品</h2>
        <div class="in">
            <form action="#" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="產品名稱" required>
                <input type="text" name="name_en" placeholder="產品英文名稱" required>
                <input type="text" name="gtin" placeholder="gtin碼" required>
                <textarea name="des" placeholder="產品說明" style="padding: 5px;border: none;border-radius: 1em;"></textarea>
                <textarea name="des_en" placeholder="產品英文說明" style="padding: 5px;border: none;border-radius: 1em;"></textarea>
                <input type="file" name="image" accept="image/*">
                <button type="submit" name="add_prod" class="button">新增</button>
            </form>
        </div>
        <div class="padd"></div>
        <table>
            <tr>
                <td>產品名稱</td>
                <td>產品英文名稱</td>
                <td>gtin碼</td>
                <td>產品說明</td>
                <td>產品英文說明</td>
                <td>圖片</td>
                <td>狀態</td>
                <td>控制</td>
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
                        <?php if($row['status'] == 'valid'):?>
                            <a href="prod.php?comp_id=<?=$row['comp_id']?>&action=Invalid&id=<?= $row['id']?>" style="text-decoration: none;">Invalid</a>
                        <?php elseif($row['status'] == 'Invalid'):?>
                            <a href="prod.php?comp_id=<?=$row['comp_id']?>&action=valid&id=<?= $row['id']?>" style="text-decoration: none;">valid</a>
                        <?php endif;?>
                    </th>
                </tr>
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