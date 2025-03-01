<?php
include 'db.php';

$company_id = isset($_GET['company_id']) && is_numeric($_GET['company_id']) ? (int)$_GET['company_id'] : 0;
$error_messages = [];
$success_message = "";

// 處理文件上傳
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error_messages[] = "文件上傳失敗！";
    } elseif (!in_array($file_type, ['csv', 'json'])) {
        $error_messages[] = "無效的文件格式！請上傳 CSV 或 JSON 文件。";
    } else {
        if ($file_type == 'csv') {
            $error_messages = import_csv($file['tmp_name'], $company_id);
        } elseif ($file_type == 'json') {
            $error_messages = import_json($file['tmp_name'], $company_id);
        }
    }
}

// 匯入 CSV
function import_csv($file_path, $company_id) {
    $errors = [];
    $file = fopen($file_path, 'r');
    if (!$file) {
        return ["無法打開 CSV 文件！"];
    }
    fgetcsv($file); // 跳過標題行

    while (($data = fgetcsv($file)) !== FALSE) {
        if (count($data) < 6) {
            $errors[] = "CSV 格式錯誤，欄位數量不足！";
            continue;
        }
        list($name, $name_en, $gtin, $description, $description_en, $status) = array_map('trim', $data);

        if (!is_valid_gtin($gtin)) {
            $errors[] = "GTIN $gtin 格式錯誤！";
            continue;
        }

        save_product($company_id, $name, $name_en, $gtin, $description, $description_en, $status, $errors);
    }
    fclose($file);
    return empty($errors) ? ["CSV 文件匯入成功！"] : $errors;
}

// 匯入 JSON
function import_json($file_path, $company_id) {
    $errors = [];
    $json_data = file_get_contents($file_path);
    $data = json_decode($json_data, true);

    if ($data === null) {
        return ["無法解析 JSON 文件！"];
    }

    foreach ($data as $item) {
        if (!isset($item['gtin'], $item['name'], $item['name_en'], $item['description'], $item['description_en'], $item['status'])) {
            $errors[] = "JSON 格式錯誤！";
            continue;
        }

        $gtin = trim($item['gtin']);
        if (!is_valid_gtin($gtin)) {
            $errors[] = "GTIN $gtin 格式錯誤！";
            continue;
        }

        save_product($company_id, $item['name'], $item['name_en'], $gtin, $item['description'], $item['description_en'], $item['status'], $errors);
    }
    return empty($errors) ? ["JSON 文件匯入成功！"] : $errors;
}

// GTIN 驗證（僅檢查是否為 13 位數字）
function is_valid_gtin($gtin) {
    return ctype_digit($gtin) && strlen($gtin) == 13;
}

// 儲存或更新產品
function save_product($company_id, $name, $name_en, $gtin, $description, $description_en, $status, &$errors) {
    global $conn;
    $sql = "INSERT INTO products (company_id, name, name_en, gtin, description, description_en, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE name = VALUES(name), name_en = VALUES(name_en),
            description = VALUES(description), description_en = VALUES(description_en), status = VALUES(status)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $errors[] = "資料庫錯誤：" . $conn->error;
        return;
    }
    $stmt->bind_param("issssss", $company_id, $name, $name_en, $gtin, $description, $description_en, $status);
    if (!$stmt->execute()) {
        $errors[] = "無法儲存：" . $stmt->error;
    }
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
        <input type="hidden" name="company_id" value="<?= $company_id ?>"> <!-- 確保 company_id 保留 -->
        <input type="file" name="file" accept=".csv, .json" required>
        <button type="submit">匯入</button>
    </form>

    <?php if (!empty($error_messages)): ?>
        <div class="error">
            <ul>
                <?php foreach ($error_messages as $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div>
        <a href="products.php?company_id=<?= urlencode($company_id) ?>">返回產品管理</a>
    </div>
</body>
</html>
