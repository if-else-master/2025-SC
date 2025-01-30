<?php
session_start();

// 生成验证码
function generateCaptcha() {
    $captcha = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $_SESSION['captcha'] = $captcha;
    return $captcha;
}

if (!isset($_SESSION['captcha'])) {
    generateCaptcha();
}

if (isset($_POST['refresh_captcha'])) {
    generateCaptcha();
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>網站管理 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>網站管理</h2>
        <form method="post" action="verify_login.php" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">帳號</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">密碼</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="captcha" class="form-label">驗證碼</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" class="form-control" id="captcha" name="captcha" required>
                    <div class="border p-2" id="captcha-display"><?php echo $_SESSION['captcha']; ?></div>
                    <button type="button" class="btn btn-secondary" id="refresh-captcha">重新產生</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">登入</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('refresh-captcha').addEventListener('click', function() {
        fetch('admin_login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'refresh_captcha=1'
        })
        .then(() => {
            location.reload();
        });
    });
    </script>
</body>
</html>