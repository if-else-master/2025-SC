<?php
session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "message_board2";

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 登入功能
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 驗證驗證碼
    if (!isset($_POST['captcha']) || !isset($_SESSION['captcha']) ||
        $_POST['captcha'] != $_SESSION['captcha']) {
        echo "驗證碼錯誤！";
        unset($_SESSION['captcha']); // 清除舊的驗證碼
    } else {
        // 從 POST 請求中獲取使用者名稱和密碼
        $login_username = $_POST['username'];
        $login_password = $_POST['password'];

        // 檢查是否為 admin 帳號
        if ($login_username == 'admin' && $login_password == '1234') {
            $_SESSION['user_id'] = 1; // 假設 admin 的 user_id 是 1
            $_SESSION['username'] = 'admin';
            header("Location: second_verification.php");
            exit();
        } else {
            // 準備 SQL 查詢語句
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $login_username);
            $stmt->execute();
            $stmt->bind_result($id, $username, $hashed_password);
            $stmt->fetch();

            if (password_verify($login_password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: second_verification.php");
            } else {
                echo "登入失敗: 使用者名稱或密碼錯誤";
            }
            $stmt->close();
        }
    }
}

// 生成數字驗證碼的函數
function generateNumericCaptcha($length = 4) {
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha .= rand(0, 9);
    }
    return $captcha;
}

// 生成新的驗證碼並存儲在會話中
$_SESSION['captcha'] = generateNumericCaptcha(4);

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .captcha-container {
            margin: 10px 0;
        }
        .captcha-image {
            background-color: #f0f0f0;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 8px;
            font-weight: bold;
            color: #333;
            user-select: none;
            cursor: pointer;
            border-radius: 4px;
            display: inline-block;
            min-width: 100px;
            text-align: center;
        }
        .refresh-button {
            margin-left: 10px;
            padding: 5px 10px;
            cursor: pointer;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .clear-button {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .clear-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
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

    <div class="container">
        <h2>登入</h2>
        <form method="POST" action="login.php" id="loginForm">
            <label for="username">使用者名稱:</label>
            <input type="text" id="username" name="username" placeholder="請輸入帳號" required>

            <label for="password">密碼:</label>
            <input type="password" id="password" name="password" placeholder="請輸入密碼" required>

            <div class="captcha-container">
                <label for="captcha">驗證碼:</label>
                <div class="captcha-image" id="captchaDisplay"><?php echo $_SESSION['captcha']; ?></div>
                <button type="button" class="refresh-button" onclick="refreshCaptcha()">更新驗證碼</button>
                <input type="text" id="captcha" name="captcha" placeholder="請輸入數字驗證碼" required>
            </div>

            <div class="button-container">
                <button type="submit">登入</button>
                <button type="button" class="clear-button" onclick="clearForm()">清空</button>
                <a href="register.php">註冊</a>
            </div>
        </form>
    </div>

    <script>
        function refreshCaptcha() {
            fetch('refresh_captcha.php')
                .then(response => response.text())
                .then(captcha => {
                    document.getElementById('captchaDisplay').textContent = captcha;
                });
        }

        function clearForm() {
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            document.getElementById('captcha').value = '';
            refreshCaptcha(); // 同時更新驗證碼
        }

        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        // 點擊下拉選單外部時關閉選單
        window.onclick = function(event) {
            if (!event.target.matches('.hamburger') &&
                !event.target.matches('.hamburger span')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
