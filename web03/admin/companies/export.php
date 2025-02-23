<?php
require_once '../auth.php';
require_once '../../includes/db.php';

$format = $_GET['format'] ?? 'json';
$stmt = $pdo->query("SELECT * FROM companies ORDER BY name");
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($format === 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="companies.json"');
    echo json_encode($companies, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="companies.csv"');
    
    $output = fopen('php://output', 'w');
    
    // UTF-8 BOM for Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // 寫入 CSV 標題
    fputcsv($output, array_keys($companies[0]));
    
    // 寫入數據
    foreach ($companies as $company) {
        fputcsv($output, $company);
    }
    
    fclose($output);
}