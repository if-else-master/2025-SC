<?php
// 使用PHP的file_get_contents函數獲取API數據
function callAPI($method, $path, $data = null) {
    $url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/../api.php?path=" . $path;
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

// 獲取會員公司列表
$companies = json_decode(callAPI('GET', 'companies'), true);

// 如果指定了公司ID，獲取該公司的產品列表
if (isset($_GET['company_id'])) {
    $products = json_decode(callAPI('GET', 'products?company_id=' . $_GET['company_id']), true);
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理系統 - 產品資訊管理系統</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="dashboard-page">
    <header class="dashboard-header">
        <h1>產品資訊管理系統</h1>
        <nav>
            <button onclick="showSection('companies')" class="btn">會員公司管理</button>
            <button onclick="showSection('products')" class="btn">產品管理</button>
            <button onclick="window.location.href='login.php'" class="btn btn-danger">登出</button>
        </nav>
    </header>

    <main class="dashboard-main">
        <!-- 會員公司管理區塊 -->
        <section id="companies" class="section">
            <div class="section-header">
                <h2>會員公司管理</h2>
                <button onclick="showCompanyForm()" class="btn btn-primary">新增會員公司</button>
                <div class="status-filter">
                    <button onclick="loadCompanies('active')" class="btn">活動中</button>
                    <button onclick="loadCompanies('inactive')" class="btn">已停用</button>
                </div>
            </div>
            <div id="companiesList" class="list-container">
                <?php if ($companies && isset($companies['data'])): ?>
                    <?php foreach ($companies['data'] as $company): ?>
                        <div class="company-card">
                            <h3><?php echo htmlspecialchars($company['name']); ?></h3>
                            <p>地址：<?php echo htmlspecialchars($company['address']); ?></p>
                            <p>電話：<?php echo htmlspecialchars($company['phone']); ?></p>
                            <p>Email：<?php echo htmlspecialchars($company['email']); ?></p>
                            <div class="card-actions">
                                <button onclick="editCompany(<?php echo $company['id']; ?>)" class="btn">編輯</button>
                                <button onclick="deleteCompany(<?php echo $company['id']; ?>)" class="btn btn-danger">刪除</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- 產品管理區塊 -->
        <section id="products" class="section" style="display: none;">
            <div class="section-header">
                <h2>產品管理</h2>
                <div class="product-actions">
                    <select id="companySelect" onchange="loadCompanyProducts()">
                        <option value="">選擇會員公司</option>
                        <?php if ($companies && isset($companies['data'])): ?>
                            <?php foreach ($companies['data'] as $company): ?>
                                <option value="<?php echo $company['id']; ?>">
                                    <?php echo htmlspecialchars($company['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button onclick="showProductForm()" class="btn btn-primary">新增產品</button>
                    <button onclick="showImportForm()" class="btn">匯入產品</button>
                    <button onclick="exportProducts()" class="btn">匯出產品</button>
                </div>
            </div>
            <div id="productsList" class="list-container">
                <?php if (isset($products) && isset($products['data'])): ?>
                    <?php foreach ($products['data'] as $product): ?>
                        <div class="product-card">
                            <?php if (!empty($product['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name_zh']); ?>">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($product['name_zh']); ?></h3>
                            <p>GTIN: <?php echo htmlspecialchars($product['gtin']); ?></p>
                            <div class="card-actions">
                                <button onclick="editProduct(<?php echo $product['id']; ?>)" class="btn">編輯</button>
                                <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="btn btn-danger">刪除</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="../js/dashboard.js"></script>
</body>
</html>