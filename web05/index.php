<?php
// 使用PHP的file_get_contents函數獲取API數據
function callAPI($method, $path, $data = null) {
    $url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/api.php?path=" . $path;
    $options = [
        'http' => [
            'method' => $method,
            'header' => 'Content-Type: application/json',
            'content' => $data ? json_encode($data) : null
        ]
    ];
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>產品資訊管理系統</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="images/logo.png" alt="網站LOGO" class="logo">
        </div>
        <div class="title-container">
            <h1>產品資訊管理系統</h1>
        </div>
        <div class="button-container">
            <button onclick="window.location.href='admin/login.php'" class="btn btn-primary">管理</button>
            <button onclick="window.location.href='gtin-validation.php'" class="btn btn-secondary">GTIN批次驗證</button>
        </div>
    </header>

    <main>
        <section class="photo-showcase">
            <?php
            // 獲取所有產品資料
            $products = json_decode(callAPI('GET', 'products'), true);
            if ($products && isset($products['data'])) {
                foreach ($products['data'] as $product) {
                    if (!empty($product['image_path'])) {
                        echo "<div class='product-image'>";
                        echo "<img src='{$product['image_path']}' alt='{$product['name_zh']}'>";
                        echo "<p>{$product['name_zh']}</p>";
                        echo "</div>";
                    }
                }
            }
            ?>
        </section>

        <section class="content">
            <div class="companies-container">
                <?php
                // 獲取所有會員公司資料
                $companies = json_decode(callAPI('GET', 'companies'), true);
                if ($companies && isset($companies['data'])) {
                    foreach ($companies['data'] as $company) {
                        echo "<div class='company-card'>";
                        echo "<h3>{$company['name']}</h3>";
                        echo "<p>地址：{$company['address']}</p>";
                        echo "<p>電話：{$company['phone']}</p>";
                        echo "<p>Email：{$company['email']}</p>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 產品資訊管理系統. All rights reserved.</p>
    </footer>
</body>
</html>