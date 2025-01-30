<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_code'])) {
    $message_code = $_POST['message_code'];
    
    // 驗證留言序號格式
    if (preg_match('/^[0-9a-z]{3}$/', $message_code)) {
        $sql = "DELETE FROM messages WHERE message_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $message_code);
        
        if ($stmt->execute()) {
            header('Location: guest_message.php?success=deleted');
        } else {
            header('Location: guest_message.php?error=delete_failed');
        }
    } else {
        header('Location: guest_message.php?error=invalid_code');
    }
} else {
    header('Location: guest_message.php');
}
exit();
?>