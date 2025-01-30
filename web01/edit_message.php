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

$message_id = $_GET['id'];
$message_code = $_POST['message_code'];

// 獲取留言內容
$stmt = $conn->prepare("SELECT * FROM messages WHERE id = ? AND message_code = ?");
$stmt->bind_param("is", $message_id, $message_code);
$stmt->execute();
$result = $stmt->get_result();
$message = $result->fetch_assoc();
$stmt->close();

// 修改留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_message'])) {
    $new_message = $_POST['new_message'];
    $new_email = $_POST['new_email'];
    $new_phone = $_POST['new_phone'];
    $show_email_phone = isset($_POST['show_email_phone']) ? 1 : 0;

    // 檢查 Email 和連絡電話格式
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "Email 格式錯誤";
        exit();
    }
    if (!preg_match('/^[0-9-]+$/', $new_phone)) {
        echo "連絡電話格式錯誤";
        exit();
    }

    $stmt = $conn->prepare("UPDATE messages SET message = ?, email = ?, phone = ?, show_email_phone = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND message_code = ?");
    $stmt->bind_param("sssisi", $new_message, $new_email, $new_phone, $show_email_phone, $message_id, $message_code);
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "留言修改失敗: " . $stmt->error;
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
    <title>修改留言</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>修改留言</h2>
        <form method="POST" action="edit_message.php?id=<?php echo $message_id; ?>">
            <label for="new_message">新留言:</label>
            <textarea id="new_message" name="new_message" required><?php echo $message['message']; ?></textarea>

            <label for="new_email">新 Email:</label>
            <input type="email" id="new_email" name="new_email" value="<?php echo $message['email']; ?>" required>

            <label for="new_phone">新連絡電話:</label>
            <input type="text" id="new_phone" name="new_phone" value="<?php echo $message['phone']; ?>" required>

            <label for="show_email_phone">顯示 Email 及連絡電話:</label>
            <input type="checkbox" id="show_email_phone" name="show_email_phone" <?php echo $message['show_email_phone'] ? 'checked' : ''; ?>>

            <button type="submit">修改</button>
        </form>
        <a href="index.php">返回留言板</a>
    </div>
</body>
</html>
