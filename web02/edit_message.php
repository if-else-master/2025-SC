<?php
require_once 'db_connect.php';

// 處理刪除請求
if (isset($_POST['delete']) && isset($_POST['message_code'])) {
    $message_code = $_POST['message_code'];
    $sql = "DELETE FROM messages WHERE message_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $message_code);
    
    if ($stmt->execute()) {
        header('Location: guest_message.php');
        exit();
    }
}

if (isset($_GET['code'])) {
    $message_code = $_GET['code'];
    $sql = "SELECT * FROM messages WHERE message_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $message_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = $result->fetch_assoc();
    
    if (!$message) {
        header('Location: guest_message.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    $content = $_POST['content'];
    $show_email = isset($_POST['show_email']) ? 1 : 0;
    $show_phone = isset($_POST['show_phone']) ? 1 : 0;
    $updated_at = date('Y-m-d H:i:s');
    
    $sql = "UPDATE messages SET content = ?, updated_at = ?, show_email = ?, show_phone = ? WHERE message_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiis", $content, $updated_at, $show_email, $show_phone, $message_code);
    
    if ($stmt->execute()) {
        header('Location: guest_message.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯留言 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>編輯留言</h2>
        <?php if ($message): ?>
            <form method="post" action="">
                <input type="hidden" name="message_code" value="<?php echo htmlspecialchars($message['message_code']); ?>">
                
                <div class="mb-3">
                    <label for="content" class="form-label">留言內容</label>
                    <textarea class="form-control" id="content" name="content" rows="3" required><?php echo htmlspecialchars($message['content']); ?></textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="show_email" name="show_email" 
                               <?php echo $message['show_email'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="show_email">
                            顯示 Email
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="show_phone" name="show_phone"
                               <?php echo $message['show_phone'] ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="show_phone">
                            顯示連絡電話
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">更新留言</button>
                <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('確定要刪除這則留言嗎？')">刪除留言</button>
                <a href="guest_message.php" class="btn btn-secondary">返回</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>