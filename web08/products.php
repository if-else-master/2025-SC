<?php
include("db.php");


$company_id = $_GET['company_id'];

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_product'])){
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $description = $_POST['description'];
    $description_en = $_POST['description_en'];

    if(strlen($gtin)!=13 || !ctype_digit($gtin)){
        die('無效的GTIN格式');
    }

    $image_path = 'upload/2021.png'; // 預設圖片

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        die("檔案上傳錯誤，錯誤碼：" . ($_FILES['image']['error'] ?? '未上傳檔案'));
    }

    $upload_dir = 'upload/';
    if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
        die('無法創建上傳目錄！');
    }

    $image_path = $upload_dir . basename($_FILES['image']['name']);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        echo "圖片上傳成功！<br>上傳檔案路徑：$image_path";
    } else {
        die("圖片上傳失敗！");
    }

    $sql = "INSERT INTO `products`(`company_id`, `name`, `name_en`, `gtin`, `description`, `description_en`, `image_path`) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss",$company_id,$name,$name_en,$gtin,$description,$description_en,$image_path);
    $stmt->execute();
}

$sql = "SELECT * FROM products WHERE company_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$company_id);
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
    <div class="container">
        <h1>產品系統</h1>      
    </div>
    <h2>會員公司管理</h2>
    <form method="POST" enctype="multipart/form-data">
        <h3>新增產品</h3>
        <input type="text" name="name" placeholder=" 產品名稱" required>
        <input type="text" name="name_en" placeholder="產品英文名稱" required>
        <input type="text" name="gtin" placeholder="GTIN碼" required>
        <textarea type="description" name="description" placeholder="產品介紹" required></textarea>
        <textarea type="description_en" name="description_en" placeholder="產品英文介紹" required></textarea>
        <input type="file" name="image" accept="image/*" required >
        <button type="submit" name="add_product">新增</button> 
    </form>
    <h3>公司會員列表</h3>
    <table>
        <tr>
            <th>公司名稱</th>
            <th>地址</th>
            <th>電話</th>
            <th>電子郵件</th>
            <th>擁有者姓名</th>
            <th>狀態</th>
            <th>操作</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['address'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['owner_name'] ?></td>
                <td><?= $row['status'] == 'active' ? '啟用' : '停用' ?></td>
                <td>
                    <?php if ($row['status'] == 'active'): ?>
                        <a href="?action=deactivate&id=<?= $row['id'] ?>">停用</a>
                    <?php else: ?>
                        <a href="?action=activate&id=<?= $row['id'] ?>">啟用</a>
                    <?php endif; ?>
                    <a href="products.php?company_id=<?= $row['id'] ?>">查看產品</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>    
</body>
</html>



