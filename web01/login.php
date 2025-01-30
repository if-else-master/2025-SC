<?php
session_start();
<<<<<<< HEAD
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "message_board2";
=======
$servername = "127.0.0.1";#伺服器IP
$username = "root";#伺服器帳號
$password = "";#伺服器密碼
$dbname = "message_board";#資料庫名稱
>>>>>>> parent of 2f34e82 (update login)

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 登入功能
// 檢查請求方法是否為 POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 從 POST 請求中獲取使用者名稱和密碼
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];
    // 準備 SQL 查詢語句，使用預處理語句來防止 SQL 注入
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    // 綁定使用者名稱參數到預處理語句
    $stmt->bind_param("s", $login_username);
    // 執行預處理語句
    $stmt->execute();
    // 綁定結果變數到預處理語句
    $stmt->bind_result($id, $username, $hashed_password);
    // 獲取查詢結果
    $stmt->fetch();
    // 驗證輸入的密碼是否與存儲的哈希密碼匹配
    if (password_verify($login_password, $hashed_password)) {
        // 如果驗證成功，設置會話變數
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;

        // 重定向到 messages.php 頁面
        header("Location: messages.php");
    } else {
        // 如果驗證失敗，輸出錯誤訊息
        echo "登入失敗: 使用者名稱或密碼錯誤";
    }

    // 關閉預處理語句
    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<<<<<<< HEAD
    <div class="navbar">
        <div class="main-nav">
            <a href="index.php">快樂旅遊網</a>
            <a href="rootmsg.php">訪客留言</a>
            <a href="#">訪客訂房</a>
            <a href="#">訪客訂餐</a>
            <a href="login.php">網站管理</a>
        </div>
        <div class="dropdown">
            <div class="hamburger" onclick="toggleDropdown()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="dropdown-content" id="dropdownMenu">
                <a href="index.php">快樂旅遊網</a>
                <a href="#">訪客留言</a>
                <a href="#">訪客訂房</a>
                <a href="#">訪客訂餐</a>
                <a href="login.php">網站管理</a>
            </div>
        </div>
    </div>

=======
>>>>>>> parent of 2f34e82 (update login)
    <div class="container">
        <h2>登入</h2>
        <form method="POST" action="login.php">
            <label for="username">使用者名稱:</label>
            <input type="text" id="username" name="username" placeholder="請輸入帳號" required>
            <label for="password">密碼:</label>
            <input type="password" id="password" name="password" placeholder="請輸入密碼" required>
            <button type="submit">登入</button>
            <a href="register.php">註冊</a>
        </form>
    </div>
</body>
</html>
