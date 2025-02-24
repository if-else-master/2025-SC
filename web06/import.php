<?php
include 'db.php';

$company_id = $_GET['company_id'];
$error_message = "";

// 處理文件上傳
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $file_type = pathinfo($file['name'], PATHINFO_EXTENSION);

    // 檢查文件類型
    if ($file_type == 'csv') {
        $error_message = import_csv($file['tmp_name'], $company_id);
    } elseif ($file_type == 'json') {
        $error_message = import_json($file['tmp_name'], $company_id);
    } else {
        $error_message = "無效的文件格式！請上傳 CSV 或 JSON 文件。";
    }
}

// 匯入 CSV 文件
function import_csv($file_path, $company_id) {
    $file = fopen($file_path, 'r');
    if (!$file) {
        return "無法打開 CSV 文件！";
    }

    // 跳過標題行
    fgetcsv($file);

    while (($data = fgetcsv($file)) !== FALSE) {
        $name = $data[0];
        $name_en = $data[1];
        $gtin = $data[2];
        $description = $data[3];
        $description_en = $data[4];
        $status = $data[5];

        // 驗證 GTIN
        if (strlen($gtin) != 13 || !ctype_digit($gtin)) {
            return "無效的 GTIN 格式！";
        }

        // 插入資料庫
        $sql = "INSERT INTO products (company_id, name, name_en, gtin, description, description_en, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->bind_param("issssss", $company_id, $name, $name_en, $gtin, $description, $description_en, $status);
        if (!$stmt->execute()) {
            return "匯入資料時發生錯誤！";
        }
    }

    fclose($file);
    return "CSV 文件匯入成功！";
}

// 匯入 JSON 文件
function import_json($file_path, $company_id) {
    $json_data = file_get_contents($file_path);
    $data = json_decode($json_data, true);

    if ($data === null) {
        return "無法解析 JSON 文件！";
    }

    foreach ($data as $item) {
        $name = $item['name'];
        $name_en = $item['name_en'];
        $gtin = $item['gtin'];
        $description = $item['description'];
        $description_en = $item['description_en'];
        $status = $item['status'];

        // 驗證 GTIN
        if (strlen($gtin) != 13 || !ctype_digit($gtin)) {
            return "無效的 GTIN 格式！";
        }

        // 插入資料庫
        $sql = "INSERT INTO products (company_id, name, name_en, gtin, description, description_en, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->bind_param("issssss", $company_id, $name, $name_en, $gtin, $description, $description_en, $status);
        if (!$stmt->execute()) {
            return "匯入資料時發生錯誤！";
        }
    }

    return "JSON 文件匯入成功！";
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>匯入產品</title>
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
    <h2>匯入產品</h2>
    <form method="POST" enctype="multipart/form-data">
        <h3>上傳 CSV 或 JSON 文件</h3>
        <input type="file" name="file" accept=".csv, .json" required>
        <button type="submit">匯入</button>
    </form>

    <?php if ($error_message): ?>
        <div class="error">
            <?= $error_message ?>
        </div>
    <?php endif; ?>

    <div>
        <a href="products.php?company_id=<?= $company_id ?>">返回產品管理</a>
    </div>
</body>
</html>
