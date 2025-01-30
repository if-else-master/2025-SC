<?php
session_start();

// 檢查是否已完全驗證
if (!isset($_SESSION['fully_authenticated']) || $_SESSION['fully_authenticated'] !== true) {
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理後台 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>網站管理</h2>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">留言管理</h5>
                        <p class="card-text">管理網站訪客留言</p>
                        <a href="admin_message.php" class="btn btn-primary">進入管理</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">訂房管理</h5>
                        <p class="card-text">管理訪客訂房資訊</p>
                        <a href="admin_booking.php" class="btn btn-primary">進入管理</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>