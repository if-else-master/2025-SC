<?php
require_once '../auth.php';
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // GTIN 格式验证
    if (!preg_match('/^\d{13}$/', $_POST['gtin'])) {
        $_SESSION['message'] = 'GTIN 必須為13位數字';
        header('Location: create.php');
        exit;
    }

    // 处理图片上传
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $image_path = 'uploads/products/' . $file_name;
        
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name);
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO products (
                company_id, name_zh, name_en, gtin, 
                description_zh, description_en, image_path
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_POST['company_id'],
            $_POST['name_zh'],
            $_POST['name_en'],
            $_POST['gtin'],
            $_POST['description_zh'],
            $_POST['description_en'],
            $image_path
        ]);

        $_SESSION['message'] = '產品新增成功';
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['message'] = '新增失敗：' . $e->getMessage();
        header('Location: create.php');
        exit;
    }
}

header('Location: create.php');
exit;