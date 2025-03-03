<?php
include 'db.php';

$comp_id = isset($_GET['comp_id']) ? (int)$_GET['comp_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $file_type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($file_type == 'csv') {
        import_csv($file['tmp_name'], $comp_id);
    } elseif ($file_type == 'json') {
        import_json($file['tmp_name'], $comp_id);
    }
}

function import_csv($file_path, $comp_id) {
    $file = fopen($file_path, 'r');
    fgetcsv($file);
    while ($data = fgetcsv($file)) {
        save_product($comp_id, ...$data);
    }
    fclose($file);
}

function import_json($file_path, $comp_id) {
    $data = json_decode(file_get_contents($file_path), true);
    foreach ($data as $item) {
        save_product($comp_id, $item['name'], $item['name_en'], $item['gtin'], $item['des'], $item['des_en'], $item['status']);
    }
}

function save_product($comp_id, $name, $name_en, $gtin, $des, $des_en, $status) {
    global $conn;
    $sql = "INSERT INTO prods (comp_id, name, name_en, gtin, des, des_en, status)
            VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), name_en=VALUES(name_en), des=VALUES(des), des_en=VALUES(des_en), status=VALUES(status)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $comp_id, $name, $name_en, $gtin, $des, $des_en, $status);
    $stmt->execute();
    header("Location:prod.php?comp_id=$comp_id");
    
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv, .json" required>
    <button type="submit">匯入</button>
</form>
