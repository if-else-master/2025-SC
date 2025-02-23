<?php
// 檢查是否有提交登入表單
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/../api.php?path=login";
    $data = json_encode([
        'username' => $_POST['username'],
        'password' => $_POST['password']
    ]);
    
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $data
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result, true);
    
    if ($response['success']) {
        header('Location: dashboard.php');
        exit;
    }
    $error_message = $response['message'];
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員登入 - 產品資訊管理系統</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h2>管理員登入</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">帳號</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">密碼</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">登入</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='../index.php'">返回首頁</button>
                </div>
                <?php if (isset($error_message)): ?>
                <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>