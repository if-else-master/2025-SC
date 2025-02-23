<?php
require_once '../auth.php';
require_once '../../includes/db.php';

header('Content-Type: application/json');

if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => '檔案上傳失敗']);
    exit;
}

$file = $_FILES['file'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, ['json', 'csv'])) {
    echo json_encode(['success' => false, 'message' => '不支援的檔案格式']);
    exit;
}

try {
    $data = [];
    if ($ext === 'json') {
        $content = file_get_contents($file['tmp_name']);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON 格式錯誤');
        }
    } else {
        $handle = fopen($file['tmp_name'], 'r');
        $headers = fgetcsv($handle);
        
        while ($row = fgetcsv($handle)) {
            $data[] = array_combine($headers, $row);
        }
        
        fclose($handle);
    }

    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("INSERT INTO companies (name, phone, email, is_active) VALUES (?, ?, ?, ?) 
                          ON DUPLICATE KEY UPDATE phone = VALUES(phone), email = VALUES(email)");
    
    foreach ($data as $company) {
        $stmt->execute([
            $company['name'],
            $company['phone'],
            $company['email'],
            $company['is_active'] ?? 1
        ]);
    }
    
    $pdo->commit();
    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}