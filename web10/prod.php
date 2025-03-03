<?php
include "db.php";

$comp_id = $_GET['comp_id'];

if(isset($_GET['action']) && isset($_GET['prod_id'])){
    $prod_id = $_GET['prod_id'];
    if($_GET['action'] =='deactivate'){
        $new_status = 'inactive';
    }else{
        $new_status = 'active';
    }

    $sql = "UPDATE prods SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$new_status,$prod_id);
    $stmt->execute();
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_pord'])){
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $des = $_POST['des'];
    $des_en = $_POST['des_en'];

    if(strlen($gtin) != 13){
        echo "無效的GTIN碼";
    }

    
    $image_path = 'uploads/aa.png'; # 預設圖片
    $upload_dir = 'uploads/';

    if (!empty($_FILES['image']['name'])) {
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    

    $sql = "INSERT INTO `prods`(`comp_id`, `name`, `name_en`, `gtin`, `des`, `des_en`, `image_path`) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss",$comp_id,$name,$name_en,$gtin,$des,$des_en,$image_path);
    $stmt->execute();
}

$sql = "SELECT * FROM `prods` WHERE  comp_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i',$comp_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/cont.css">
</head>
<body>
    <div>
        <h1>產品管理系統</h1>
    </div>
    <h2>產品管理</h2>
    <form method="POST" enctype="multipart/form-data">
        <h3>新增產品</h3>
        <input type="text" name="name" placeholder="產品名稱" required>
        <input type="text" name="name_en" placeholder="產品英文名稱" required>
        <input type="text" name="gtin" placeholder="產品GTIN碼" required>
        <textarea name="des" placeholder="產品說明" required></textarea>
        <textarea name="des_en" placeholder="產品英文說明" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add_pord">新增</button>
    </form>
    <h3>產品列表</h3>
    <table>
        <tr>
            <th>產品名稱</th>
            <th>產品英文名稱</th>
            <th>產品GTIN碼</th>
            <th>圖片</th>
            <th>狀態</th>
            <th></th>
        </tr>
        <?php while ($row = $result->fetch_assoc()):?>
            <tr>
                <td><?= htmlspecialchars($row['name'])?></td>
                <td><?= htmlspecialchars($row['name_en'])?></td>
                <td><?= htmlspecialchars($row['gtin'])?></td>
                <td>
                    <?php if(file_exists($row['image_path'])): ?>
                        <img src="<?= htmlspecialchars($row['image_path'])?>"alt="產品圖片" style="max-width: 100px;">
                    <?php else: ?>
                        無圖片
                    <?php endif; ?>
                </td>
                <td><?=($row['status'] == 'active') ? '啟用':'停用'?></td>
                <td>
                    <a href="?action=<?= ($row['status']=='active')? 'deactivate':'activate'?>&prod_id=<?= $row['id']?>&comp_id=<?= $comp_id?>">
                        <?= ($row['status'] == 'active')? '停用':'啟用'?>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
    </table>
    <div class="padding"></div>
    <div>
        <a href="comp.php">回首頁</a>
        <a href="import.php?comp_id=<?= $comp_id ?>">匯入列表</a>
        <a href="export.php?comp_id=<?= urlencode($comp_id)?>&format=csv">匯出 CSV</a>
        <a href="export.php?comp_id=<?= urlencode($comp_id)?>&format=json">匯出 JSON</a>
    </div>
</body>
</html>