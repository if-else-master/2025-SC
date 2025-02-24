<?php
include 'db.php';

$company_id = $_GET['company_id'];
$format = $_GET['format'];

// 取得產品資料
$sql = "SELECT * FROM products WHERE company_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

// 匯出為 CSV
if ($format == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="products.csv"');

    $fp = fopen('php://output', 'w');
    fputcsv($fp, ['名稱 (中文)', '名稱 (英文)', 'GTIN', '描述 (中文)', '描述 (英文)', '狀態']);
    foreach ($products as $product) {
        fputcsv($fp, [
            $product['name'],
            $product['name_en'],
            $product['gtin'],
            $product['description'],
            $product['description_en'],
            $product['status']
        ]);
    }
    fclose($fp);
}

// 匯出為 JSON
if ($format == 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="products.json"');
    echo json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>
