<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "message_board";

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'];
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    if ($stmt->execute()) {
        echo "留言成功";
    } else {
        echo "留言失敗: " . $stmt->error;
    }
    $stmt->close();
}

// 刪除留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $message_id = $_POST['message_id'];
    $user_id = $_SESSION['user_id'];

    // 管理者可以刪除任何留言
    if ($_SESSION['username'] == 'admin') {
        $stmt = $conn->prepare("UPDATE messages SET deleted = 1 WHERE id = ?");
        $stmt->bind_param("i", $message_id);
    } else {
        $stmt = $conn->prepare("UPDATE messages SET deleted = 1 WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $message_id, $user_id);
    }

    if ($stmt->execute()) {
        echo "留言刪除成功";
    } else {
        echo "留言刪除失敗: " . $stmt->error;
    }
    $stmt->close();
}

// 顯示留言
$stmt = $conn->prepare("SELECT messages.id, users.username, messages.message, messages.created_at, messages.deleted FROM messages JOIN users ON messages.user_id = users.id");
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
</head>
<body>
    <div class="container">
        <h2>留言板</h2>
        <form method="POST" action="messages.php">
            <label for="message">留言:</label>
            <textarea id="message" name="message" required></textarea>
            <button type="submit">留言</button>
        </form>
        <h3>所有留言</h3>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="message">
                <?php if ($row['deleted'] == 1): ?>
                    <p><strong>留言ID:</strong> <?php echo $row['id']; ?></p>
                    <p><strong>狀態:</strong> 已刪除</p>
                <?php else: ?>
                    <p><strong>留言ID:</strong> <?php echo $row['id']; ?></p>
                    <p><strong>使用者:</strong> <?php echo $row['username']; ?></p>
                    <p><strong>留言內容:</strong> <?php echo $row['message']; ?></p>
                    <p><strong>留言時間:</strong> <?php echo $row['created_at']; ?></p>
                    <?php if ($_SESSION['username'] == 'admin' || $row['username'] == $_SESSION['username']): ?>
                        <div class="edit-delete">
                            <a href="edit_message.php?id=<?php echo $row['id']; ?>">修改</a>
                            <form method="POST" action="messages.php" style="display:inline;">
                                <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete">刪除</button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
        <a href="logout.php">登出</a>
    </div>
</body>
</html>
