<?php
include "db.php";

$company_id = $_GET['company_id'];

if (!empty($_GET['action']) && !empty($_GET['product_id'])) {
    $sql = "UPDATE products SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $status = ($_GET['action'] === 'deactivate' ? 'inactive' : 'active');
    $stmt->bind_param("si", $status, $_GET['product_id']);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] =='POST' && isset($_POST['add_product'])) {
    # code...
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $description = $_POST['des'];
    $description_en = $_POST['des_en'];

    if(strlen($gtin) != 13){
        echo'無效的GTIN格式';
    }

    $image_path = 'upload/2021.png';
    if (!isset($_FILES['image']) || $_FILES['image']['error']!== UPLOAD_ERR_OK) {
        die('檔案上傳錯誤');
    }
    $upload_dir = 'upload/';

    $image_path = $upload_dir . basename($_FILES['image']['name']);
    if(move_uploaded_file($_FILES['image']['tmp_name'], $image_path)){
        echo "圖片上傳成功";
    }else{
        echo "圖片上傳失敗";
    }

    $sql = "INSERT INTO `products`(`company_id`, `name`, `name_en`, `gtin`, `description`, `description_en`, `image_path`) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss",$company_id,$name,$name_en,$gtin,$description,$description_en,$image_path);
    if ($stmt->execute()) {
        echo "產品新增成功！<br>";
    } else {
        die("資料庫操作失敗：" . $stmt->error);
    }
}

$sql = "SELECT * FROM `products` WHERE company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i',$company_id);
$stmt->execute();
$result = $stmt->get_result();
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
    <div>
        <h1>產品管理系統</h1>        
    </div>
    <h2>產品管理</h2>
    <form method="POST" enctype="multipart/form-data">
        <h3>新增產品</h3>
        <input type="text" name="name" placeholder="產品名稱" required>
        <input type="text" name="name_en" placeholder="產品名稱英文" required>
        <input type="text" name="gtin" placeholder="GTIN碼" required>
        <textarea name="description" placeholder="產品描述中文" required></textarea>
        <textarea name="description_en" placeholder="產品描述英文" required></textarea>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add_product">新增</button>
    </form>

    <h3>產品列表</h3>
    <table>
        <tr>
            <th>產品名稱</th>
            <th>產品英文名稱</th>
            <th>GTIN碼</th>
            <th>圖片</th>
            <th>狀態</th>            
            <th></th>            
        </tr>
        <?php while ($row = $result->fetch_assoc()):?>
        <tr>
            <td><?=htmlspecialchars($row['name'])?></td>
            <td><?=htmlspecialchars($row['name_en'])?></td>
            <td><?=htmlspecialchars($row['gtin'])?></td>
            <td>
                <?php if(file_exists($row['image_path'])):?>
                    <img src="<?= htmlspecialchars($row['image'])?>" alt="產品圖片" style="max-width:100px;">
                <?php else: ?>                
                    無圖片
                <?php endif;?>
            </td>
            <td><?= ($row['status']=='active')? '啟用':'停用' ?></td>
            <td>
                <a href="?action=<?= ($row['status'] == 'active') ? 'deactivate' : 'activate' ?>&product_id=<?= $row['id'] ?>&company_id=<?= $company_id ?>">
                    <?= ($row['status'] == 'active') ? '停用' : '啟用' ?>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>