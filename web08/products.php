<?php
include("db.php");

$company_id = $_GET['company_id'];

if(isset($_GET['action']) && isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];
    $new_status = ($_GET['action'] == 'deactivate') ? 'inactive' : 'active';
    
    $sql = "UPDATE products SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $product_id);
    $stmt->execute();
}

// 新增產品
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $description = $_POST['description'];
    $description_en = $_POST['description_en'];

    // 驗證 GTIN (必須為13位數字)
    if (strlen($gtin) != 13 || !ctype_digit($gtin)) {
        die("無效的 GTIN 格式！");
    }


    #chomd -R 0777 uploads
    // 處理圖片上傳
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

    // 插入資料庫
    $sql = "INSERT INTO products (company_id, name, name_en, gtin, description, description_en, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("資料庫準備失敗：" . $conn->error);
    }
    $stmt->bind_param("issssss", $company_id, $name, $name_en, $gtin, $description, $description_en, $image_path);
    if ($stmt->execute()) {
        echo "產品新增成功！<br>";
    } else {
        die("資料庫操作失敗：" . $stmt->error);
    }
}
// 取得產品列表
$sql = "SELECT * FROM products WHERE company_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("資料庫查詢準備失敗：" . $conn->error);
}
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>產品管理</title>
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
    <div class="container">
        <h1>產品管理系統</h1>
    </div>
    <h2>產品管理</h2>
    <form method="POST" enctype="multipart/form-data">
        <h3>新增產品</h3>
        <input type="text" name="name" placeholder="產品名稱 (中文)" required>
        <input type="text" name="name_en" placeholder="產品名稱 (英文)" required>
        <input type="text" name="gtin" placeholder="GTIN (13 碼)" required>
        <textarea name="description" placeholder="產品描述 (中文)" required></textarea>
        <textarea name="description_en" placeholder="產品描述 (英文)" required></textarea>
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
            <th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['name_en']) ?></td>
                <td><?= htmlspecialchars($row['gtin']) ?></td>
                <td>
                    <?php if (file_exists($row['image_path'])): ?>
                        <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="產品圖片" style="max-width: 100px;">
                    <?php else: ?>
                        無圖片
                    <?php endif; ?>
                </td>
                <td><?= ($row['status'] == 'active') ? '啟用' : '停用' ?></td>
                <td>
                    <a href="?action=<?= ($row['status'] == 'active') ? 'deactivate' : 'activate' ?>&product_id=<?= $row['id'] ?>&company_id=<?= $company_id ?>">
                        <?= ($row['status'] == 'active') ? '停用' : '啟用' ?>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>    
    <div class="padding"></div>
    <div>
        <a href="companies.php">返回</a>
        <a href="import.php?company_id=<?= $company_id ?>">匯入列表</a>
        <a href="export.php?company_id=<?= urlencode($company_id)?>&format=csv">匯出為 CSV</a>
        <a href="export.php?company_id=<?= urlencode($company_id)?>&format=json">匯出為 JSON</a>
    </div>
</body>
</html>
