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

// 留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $name = $_POST['name'];
    $message = $_POST['message'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message_code = $_POST['message_code'];
    $show_email_phone = isset($_POST['show_email_phone']) ? 1 : 0;

    // 檢查 Email 和連絡電話格式
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email 格式錯誤";
        exit();
    }
    if (!preg_match('/^[0-9-]+$/', $phone)) {
        echo "連絡電話格式錯誤";
        exit();
    }

    // 上傳圖片
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $target_file;
        } else {
            echo "圖片上傳失敗";
            exit();
        }
    }

    $stmt = $conn->prepare("INSERT INTO messages (name, message, email, phone, image, message_code, show_email_phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $name, $message, $email, $phone, $image, $message_code, $show_email_phone);
    if ($stmt->execute()) {
        echo "留言成功";
    } else {
        echo "留言失敗: " . $stmt->error;
    }
    $stmt->close();
}

// 顯示留言
$stmt = $conn->prepare("SELECT * FROM messages WHERE is_hidden = 0 ORDER BY is_top DESC, created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言板</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .message-container {
            margin-bottom: 20px;
        }
        .message-image img {
            max-width: 300px;
            width: 100%;
            height: auto;
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
        <h2>留言板</h2>
        <form method="POST" action="rootmsg.php" enctype="multipart/form-data">
            <label for="name">姓名:</label>
            <input type="text" id="name" name="name" required>

            <label for="message">留言:</label>
            <textarea id="message" name="message" required></textarea>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">連絡電話:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="message_code">留言序號:</label>
            <input type="text" id="message_code" name="message_code" pattern="[a-z0-9]{3}" required>

            <label for="image">上傳圖片:</label>
            <input type="file" id="image" name="image">

            <label for="show_email_phone">顯示 Email 及連絡電話:</label>
            <input type="checkbox" id="show_email_phone" name="show_email_phone" checked>

            <button type="submit">留言</button>
        </form>

        <h3>所有留言</h3>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message-container">
                <p><strong>姓名:</strong> <?php echo $row['name']; ?></p>
                <p><strong>留言內容:</strong> <?php echo $row['message']; ?></p>
                <?php if ($row['show_email_phone']): ?>
                    <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                    <p><strong>連絡電話:</strong> <?php echo $row['phone']; ?></p>
                <?php endif; ?>
                <?php if ($row['image']): ?>
                    <div class="message-image">
                        <img src="<?php echo $row['image']; ?>" alt="留言圖片">
                    </div>
                <?php endif; ?>
                <p><strong>發表於:</strong> <?php echo date('Y/m/d H:i:s', strtotime($row['created_at'])); ?></p>
                <?php if ($row['updated_at']): ?>
                    <p><strong>修改於:</strong> <?php echo date('Y/m/d H:i:s', strtotime($row['updated_at'])); ?></p>
                <?php endif; ?>
                <?php if ($row['deleted_at']): ?>
                    <p><strong>刪除於:</strong> <?php echo date('Y/m/d H:i:s', strtotime($row['deleted_at'])); ?></p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
