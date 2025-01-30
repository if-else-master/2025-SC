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

// 獲取留言ID
$message_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// 管理者可以修改任何留言
if ($_SESSION['username'] == 'admin') {
    $stmt = $conn->prepare("SELECT message FROM messages WHERE id = ?");
    $stmt->bind_param("i", $message_id);
} else {
    $stmt = $conn->prepare("SELECT message FROM messages WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $message_id, $user_id);
}

$stmt->execute();
$stmt->bind_result($message);
$stmt->fetch();
$stmt->close();

// 修改留言功能
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_message = $_POST['new_message'];

    // 管理者可以修改任何留言
    if ($_SESSION['username'] == 'admin') {
        $stmt = $conn->prepare("UPDATE messages SET message = ? WHERE id = ?");
        $stmt->bind_param("si", $new_message, $message_id);
    } else {
        $stmt = $conn->prepare("UPDATE messages SET message = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $new_message, $message_id, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: messages.php");
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
            <textarea id="new_message" name="new_message" required><?php echo $message; ?></textarea>
            <button type="submit">修改</button>
        </form>
        <a href="messages.php">返回留言板</a>
    </div>
</body>
</html>
