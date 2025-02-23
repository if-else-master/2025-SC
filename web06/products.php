<?php
include 'db.php';

$company_id = $_GET['company_id'];

// 新增產品
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $description = $_POST['description'];
    $description_en = $_POST['description_en'];

    // 驗證 GTIN
    if (strlen($gtin) != 13 || !ctype_digit($gtin)) {
        die("無效的 GTIN 格式！");
    }

    $sql = "INSERT INTO products (company_id, name, name_en, gtin, description, description_en) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $company_id, $name, $name_en, $gtin, $description, $description_en);
    $stmt->execute();
}

// 取得產品列表
$sql = "SELECT * FROM products WHERE company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>產品管理</title>
    <link rel="stylesheet" href="css/comp.css">    
</head>
<body>
    <h2>產品管理</h2>
    <form method="POST">
        <h3>新增產品</h3>        
        <input type="text" name="name" placeholder="產品名稱 (中文)" required>
        <input type="text" name="name_en" placeholder="產品名稱 (英文)" required>
        <input type="text" name="gtin" placeholder="GTIN (13 碼)" required>
        <textarea name="description" placeholder="產品描述 (中文)" required></textarea>
        <textarea name="description_en" placeholder="產品描述 (英文)" required></textarea>
        <button type="submit" name="add_product">新增</button>                
    </form>   

    <h3>產品列表</h3>
    <table>
        <tr>
            <th>產品名稱 (中文)</th>
            <th>產品名稱 (英文)</th>
            <th>GTIN</th>
            <th>狀態</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['name_en'] ?></td>
                <td><?= $row['gtin'] ?></td>
                <td><?= $row['status'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div style="text-align: center;">
        <a href="companies.php"><button class="button">返回</button></a>
    </div>
</body>
</html>

