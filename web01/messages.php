<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



$servername = "127.0.0.1";#伺服器IP
$username = "root";#伺服器帳號
$password = "";#伺服器密碼
$dbname = "message_board";#資料庫名稱

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);


echo"".$_SESSION['username'];
// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 留言功能
// 檢查請求方法是否為 POST 且是否有設置 'message' 參數
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    // 從會話中獲取使用者 ID
    $user_id = $_SESSION['user_id'];
    // 從 POST 請求中獲取留言內容
    $message = $_POST['message'];
    // 準備 SQL 插入語句，使用預處理語句來防止 SQL 注入
    $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
    // 綁定使用者 ID 和留言內容參數到預處理語句。is 表示'整數'和'字串'
    $stmt->bind_param("is", $user_id, $message);
    // 執行預處理語句
    if ($stmt->execute()) {
        // 如果執行成功，輸出成功訊息
        echo "留言成功";
    } else {
        // 如果執行失敗，輸出失敗訊息和錯誤信息
        echo "留言失敗: " . $stmt->error;
    }
    // 關閉預處理語句
    $stmt->close();
}


// 刪除留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $message_id = $_POST['message_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $message_id, $user_id);

    if ($stmt->execute()) {
        echo "留言刪除成功";
    } else {
        echo "留言刪除失敗: " . $stmt->error;
    }

    $stmt->close();
}

// 顯示留言
// 準備SQL查詢語句，從messages和users表中選取資料
// 選取messages表中的id、users表中的username、messages表中的message和created_at欄位
// 使用JOIN將messages表和users表連接，條件是messages.user_id等於users.id
$stmt = $conn->prepare("SELECT messages.id, users.username, messages.message, messages.created_at FROM messages JOIN users ON messages.user_id = users.id");
// 執行準備好的SQL查詢語句
$stmt->execute();
// 獲取查詢結果
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
                <p><strong>留言ID:</strong> <?php echo $row['id']; ?></p>
                <p><strong>使用者:</strong> <?php echo $row['username']; ?></p>
                <p><strong>留言內容:</strong> <?php echo $row['message']; ?></p>
                <p><strong>留言時間:</strong> <?php echo $row['created_at']; ?></p>
                <?php if ($row['username'] == $_SESSION['username']): ?>
                    <div class="edit-delete">
                        <a href="edit_message.php?id=<?php echo $row['id']; ?>">修改</a>
                        <form method="POST" action="messages.php" style="display:inline;">
                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete">刪除</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
        <a href="logout.php">登出</a>
    </div>
</body>
</html>
