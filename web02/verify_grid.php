<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 設置完全驗證通過的標誌
    $_SESSION['fully_authenticated'] = true;
    
    echo json_encode(['success' => true]);
    exit();
}

header('Location: admin_login.php');
exit();