<?php
include 'db.php';

$error_message = "";
$success_message = "";
$results = [];
$products = [];
$language = isset($_GET['lang']) ? $_GET['lang'] : 'zh'; // 預設語言為中文

// 處理 GTIN 驗證
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gtins = explode("\n", trim($_POST['gtins'])); // 以換行符號分隔 GTIN
    $all_valid = true; // 標記是否所有 GTIN 都有效

    foreach ($gtins as $gtin) {
        $gtin = trim($gtin);

        // 驗證 GTIN 格式
        if (strlen($gtin) != 13 || !ctype_digit($gtin)) {
            $results[] = ["gtin" => $gtin, "status" => "Invalid (格式錯誤)"];
            $all_valid = false;
            continue;
        }

        // 查詢資料庫
        $sql = "SELECT p.*, c.name AS company_name FROM products p JOIN companies c ON p.company_id = c.id WHERE p.gtin = ? AND p.status = 'visible'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $gtin);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $results[] = ["gtin" => $gtin, "status" => "Valid"];
            $products[] = $result->fetch_assoc(); // 將有效產品資料加入數組
        } else {
            $results[] = ["gtin" => $gtin, "status" => "Invalid (未註冊或隱藏)"];
            $all_valid = false;
        }
    }

    if ($all_valid) {
        $success_message = "All valid!";
    }
}

// 根據語言選擇欄位
$name_field = $language == 'zh' ? 'name' : 'name_en';
$description_field = $language == 'zh' ? 'description' : 'description_en';
?>

<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <title>GTIN 驗證與產品查詢</title>
    <link rel="stylesheet" href="css/comp.css">
    <style>
        .valid { color: green; }
        .invalid { color: red; }
        .success { color: green; font-weight: bold; }
        .product-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .product-details img {
            display: block;
            max-width: 200px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>GTIN 驗證與產品查詢</h2>

    <!-- 語言切換按鈕 -->
    <div>
        <a href="?lang=zh">中文</a> |
        <a href="?lang=en">英文</a>
    </div>
    <div class="padding"></div>
    <!-- GTIN 批次驗證表單 -->
    <form method="POST">
        <h3>批量輸入 GTIN 號碼</h3>
        <textarea name="gtins" placeholder="請輸入多個 GTIN 號碼，每行一個" rows="10" required></textarea>
        <button type="submit">驗證</button>
    </form>

    <!-- 驗證結果 -->
    <?php if ($success_message): ?>
        <div class="success">
            <?= $success_message ?> ✅
        </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <h3>驗證結果</h3>
        <ul>
            <?php foreach ($results as $result): ?>
                <li>
                    GTIN: <?= $result['gtin'] ?> -
                    <?php if ($result['status'] == 'Valid'): ?>
                        <span class="valid"><?= $result['status'] ?></span>
                    <?php else: ?>
                        <span class="invalid"><?= $result['status'] ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- 產品詳情 -->
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-details">
                <img src="<?= $product['image_path'] ?>" alt="產品圖片">
                <h3>公司名稱: <?= $product['company_name'] ?></h3>
                <h3>產品名稱: <?= $product[$name_field] ?></h3>
                <p>GTIN: <?= $product['gtin'] ?></p>
                <p>描述: <?= $product[$description_field] ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="padding"></div>
    <div>
        <a href="index.html">返回首頁</a>
    </div>
</body>
</html>
