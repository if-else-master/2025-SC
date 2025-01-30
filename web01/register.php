<?php
$servername = "127.0.0.1";#伺服器IP
$username = "root";#伺服器帳號
$password = "";#伺服器密碼
$dbname = "message_board2";#資料庫名稱
// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 註冊功能
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_password = password_hash(password: $_POST['password'], algo: PASSWORD_DEFAULT);
    // 準備 SQL 插入語句，使用預處理語句來防止 SQL 注入
    $stmt = $conn->prepare(query: "INSERT INTO users (username, password) VALUES (?, ?)");
    // 綁定參數到預處理語句，"ss" 表示兩個字符串參數
    $stmt->bind_param("ss", $new_username, $new_password);
    // 執行預處理語句
    if ($stmt->execute()) {
        // 如果執行成功，輸出成功訊息
        echo "註冊成功";
    } else {
        // 如果執行失敗，輸出失敗訊息和錯誤信息
        echo "註冊失敗: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>註冊</h2>
        <form method="POST" action="register.php">
            <label for="username">使用者名稱:</label>
            <input type="text" id="username" name="username" placeholder="請輸入帳號" required>
            <label for="password">密碼:</label>
            <input type="password" id="password" name="password" placeholder="請輸入密碼" required>
            <button type="submit">註冊</button>
            <a href="login.php">登入</a>
        </form>
    </div>
</body>
</html>
