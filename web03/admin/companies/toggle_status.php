<?php
require_once '../auth.php';
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $stmt = $pdo->prepare("
        UPDATE companies 
        SET is_active = NOT is_active 
        WHERE id = ?
    ");

    try {
        $stmt->execute([$_POST['id']]);
        $_SESSION['message'] = '公司狀態已更新';
    } catch (PDOException $e) {
        $_SESSION['message'] = '更新失敗：' . $e->getMessage();
    }
}

header('Location: index.php');
exit;