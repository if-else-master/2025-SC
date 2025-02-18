<?php
require_once '../auth.php';
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO companies (name, address, phone, email, owner_name)
        VALUES (?, ?, ?, ?, ?)
    ");

    try {
        $stmt->execute([
            $_POST['name'],
            $_POST['address'],
            $_POST['phone'],
            $_POST['email'],
            $_POST['owner_name']
        ]);
        $_SESSION['message'] = '會員公司新增成功';
    } catch (PDOException $e) {
        $_SESSION['message'] = '新增失敗：' . $e->getMessage();
    }
}

header('Location: index.php');
exit;