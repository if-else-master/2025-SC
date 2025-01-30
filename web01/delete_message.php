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

// 刪除留言功能
$stmt = $conn->prepare("UPDATE messages SET deleted_at = CURRENT_TIMESTAMP WHERE id = ? AND message_code = ?");
$stmt->bind_param("is", $message_id, $message_code);
if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "留言刪除失敗: " . $stmt->error;
}
$stmt->close();

$conn->close();
?>
