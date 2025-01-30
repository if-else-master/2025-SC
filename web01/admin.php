<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: login.php");
    exit();
}

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

// 置頂留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['top'])) {
    $message_id = $_POST['message_id'];
    $stmt = $conn->prepare("UPDATE messages SET is_top = 1 WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->close();
}

// 隱藏留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hide'])) {
    $message_id = $_POST['message_id'];
    $stmt = $conn->prepare("UPDATE messages SET is_hidden = 1 WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->close();
}

// 顯示留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show'])) {
    $message_id = $_POST['message_id'];
    $stmt = $conn->prepare("UPDATE messages SET is_hidden = 0 WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->close();
}

// 顯示所有留言
$stmt = $conn->prepare("SELECT * FROM messages ORDER BY is_top DESC, created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者後台</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>管理者後台</h2>
        <h3>所有留言</h3>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message-container">
                <p><strong>姓名:</strong> <?php echo $row['name']; ?></p>
                <p><strong>留言內容:</strong> <?php echo $row['message']; ?></p>
                <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
                <p><strong>連絡電話:</strong> <?php echo $row['phone']; ?></p>
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
                <div class="admin-actions">
                    <form method="POST" action="admin.php" style="display:inline;">
                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="top">置頂</button>
                    </form>
                    <form method="POST" action="admin.php" style="display:inline;">
                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="hide">隱藏</button>
                    </form>
                    <form method="POST" action="admin.php" style="display:inline;">
                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="show">顯示</button>
                    </form>
                    <form method="POST" action="admin.php" style="display:inline;">
                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete">刪除</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
        <a href="logout.php">登出</a>
    </div>
</body>
</html>
