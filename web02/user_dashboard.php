<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_name']) || !isset($_SESSION['message_code'])) {
    header('Location: user_login.php');
    exit();
}

// 處理編輯留言
if (isset($_POST['edit_message'])) {
    $content = $_POST['content'];
    $message_id = $_POST['message_id'];
    $updated_at = date('Y-m-d H:i:s');
    
    // 確認是否為該用戶的留言
    $sql = "UPDATE messages SET content = ?, updated_at = ? 
            WHERE id = ? AND name = ? AND message_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $content, $updated_at, $message_id, 
                      $_SESSION['user_name'], $_SESSION['message_code']);
    $stmt->execute();
}

// 處理刪除留言
if (isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];
    $deleted_at = date('Y-m-d H:i:s');
    
    // 軟刪除，只更新刪除時間
    $sql = "UPDATE messages SET deleted_at = ? 
            WHERE id = ? AND name = ? AND message_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $deleted_at, $message_id, 
                      $_SESSION['user_name'], $_SESSION['message_code']);
    $stmt->execute();
}

// 獲取用戶的留言
$sql = "SELECT m.*, GROUP_CONCAT(
            CONCAT(r.reply_content, '|', r.created_at)
            ORDER BY r.created_at ASC
            SEPARATOR '||'
        ) as replies 
        FROM messages m 
        LEFT JOIN message_replies r ON m.id = r.message_id 
        WHERE m.name = ? AND m.message_code = ?
        GROUP BY m.id 
        ORDER BY m.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $_SESSION['user_name'], $_SESSION['message_code']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的留言 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>我的留言</h2>
            <a href="logout.php" class="btn btn-secondary">登出</a>
        </div>

        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">序號：<?php echo htmlspecialchars($row['message_code']); ?></h5>
                        <div>
                            <button class="btn btn-sm btn-primary" 
                                    onclick="showEditForm(<?php echo $row['id']; ?>)">
                                編輯
                            </button>
                            <form method="post" class="d-inline" 
                                  onsubmit="return confirm('確定要刪除此留言嗎？');">
                                <input type="hidden" name="message_id" 
                                       value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_message" 
                                        class="btn btn-sm btn-danger">刪除</button>
                            </form>
                        </div>
                    </div>
                    
                    <div id="content-<?php echo $row['id']; ?>">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        <?php if ($row['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                 alt="留言圖片" class="img-fluid mb-2" style="max-width: 300px">
                        <?php endif; ?>
                    </div>
                    
                    <div id="edit-form-<?php echo $row['id']; ?>" style="display: none;">
                        <form method="post">
                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                            <div class="mb-3">
                                <textarea name="content" class="form-control" rows="3" required><?php echo htmlspecialchars($row['content']); ?></textarea>
                            </div>
                            <button type="submit" name="edit_message" class="btn btn-primary">儲存</button>
                            <button type="button" class="btn btn-secondary" 
                                    onclick="hideEditForm(<?php echo $row['id']; ?>)">取消</button>
                        </form>
                    </div>
                    
                    <div class="text-muted mt-2">
                        <small>發表於 <?php echo date('Y/m/d H:i:s', strtotime($row['created_at'])); ?></small>
                        <?php if ($row['updated_at']): ?>
                            <br><small>修改於 <?php echo date('Y/m/d H:i:s', strtotime($row['updated_at'])); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!empty($row['replies'])): ?>
                    <div class="card-footer bg-light">
                        <h6 class="text-primary">管理員回覆：</h6>
                        <?php
                        $replies = explode('||', $row['replies']);
                        foreach ($replies as $reply):
                            if (empty($reply)) continue;
                            list($content, $time) = explode('|', $reply);
                        ?>
                            <div class="admin-reply mb-2 border-start border-primary ps-3">
                                <div class="reply-content">
                                    <?php echo nl2br(htmlspecialchars($content)); ?>
                                </div>
                                <small class="text-muted">
                                    回覆於：<?php echo date('Y/m/d H:i:s', strtotime($time)); ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
    function showEditForm(id) {
        document.getElementById('content-' + id).style.display = 'none';
        document.getElementById('edit-form-' + id).style.display = 'block';
    }

    function hideEditForm(id) {
        document.getElementById('content-' + id).style.display = 'block';
        document.getElementById('edit-form-' + id).style.display = 'none';
    }
    </script>
</body>
</html>