<?php
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['fully_authenticated']) || $_SESSION['fully_authenticated'] !== true) {
    header('Location: admin_login.php');
    exit();
}

// 處理完全刪除留言的請求
if (isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];
    
    // 先刪除相關的圖片文件
    $sql = "SELECT image_path FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($row['image_path'] && file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
    }
    
    // 刪除資料庫記錄
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
}

// 處理編輯留言請求
if (isset($_POST['edit_message'])) {
    $message_id = $_POST['message_id'];
    $content = $_POST['content'];
    $show_email = isset($_POST['show_email']) ? 1 : 0;
    $show_phone = isset($_POST['show_phone']) ? 1 : 0;
    $updated_at = date('Y-m-d H:i:s');

    $sql = "UPDATE messages SET content = ?, show_email = ?, show_phone = ?, updated_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisi", $content, $show_email, $show_phone, $updated_at, $message_id);
    $stmt->execute();
}

// 處理置頂/取消置頂請求
if (isset($_POST['toggle_pin'])) {
    $message_id = $_POST['message_id'];
    $sql = "UPDATE messages SET is_pinned = NOT is_pinned WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
}

// 處理隱藏/顯示請求
if (isset($_POST['toggle_visibility'])) {
    $message_id = $_POST['message_id'];
    $sql = "UPDATE messages SET is_hidden = NOT is_hidden WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
}

// 獲取所有留言
$sql = "SELECT * FROM messages ORDER BY is_pinned DESC, created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言管理 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .edit-form {
            display: none;
        }
        .message-image {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>留言管理</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>序號</th>
                        <th>姓名</th>
                        <th>Email</th>
                        <th>電話</th>
                        <th>內容</th>
                        <th>時間資訊</th>
                        <th>狀態</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr id="row-<?php echo $row['id']; ?>" class="<?php echo $row['is_hidden'] ? 'table-secondary' : ''; ?>">
                            <td><?php echo htmlspecialchars($row['message_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td class="content-display">
                                <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                                <?php if ($row['image_path']): ?>
                                    <br>
                                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                         alt="留言圖片" 
                                         class="message-image mt-2">
                                <?php endif; ?>
                            </td>
                            <td>
                                發表：<?php echo date('Y/m/d H:i:s', strtotime($row['created_at'])); ?><br>
                                <?php if ($row['updated_at']): ?>
                                    修改：<?php echo date('Y/m/d H:i:s', strtotime($row['updated_at'])); ?><br>
                                <?php endif; ?>
                                <?php if ($row['deleted_at']): ?>
                                    刪除：<?php echo date('Y/m/d H:i:s', strtotime($row['deleted_at'])); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['is_pinned']): ?>
                                    <span class="badge bg-warning">置頂</span><br>
                                <?php endif; ?>
                                <?php if ($row['is_hidden']): ?>
                                    <span class="badge bg-secondary">隱藏</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group-vertical">
                                    <button class="btn btn-sm btn-primary mb-1" 
                                            onclick="showEditForm(<?php echo $row['id']; ?>)">
                                        編輯
                                    </button>
                                    
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="toggle_pin" class="btn btn-sm <?php echo $row['is_pinned'] ? 'btn-warning' : 'btn-outline-warning'; ?> mb-1">
                                            <?php echo $row['is_pinned'] ? '取消置頂' : '置頂'; ?>
                                        </button>
                                    </form>
                                    
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="toggle_visibility" class="btn btn-sm <?php echo $row['is_hidden'] ? 'btn-success' : 'btn-secondary'; ?> mb-1">
                                            <?php echo $row['is_hidden'] ? '顯示' : '隱藏'; ?>
                                        </button>
                                    </form>

                                    <form method="post" class="d-inline" onsubmit="return confirm('確定要永久刪除此留言嗎？此操作無法復原。');">
                                        <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_message" class="btn btn-sm btn-danger">
                                            永久刪除
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr class="edit-form" id="edit-<?php echo $row['id']; ?>">
                            <td colspan="8">
                                <form method="post" class="p-3 bg-light">
                                    <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">留言內容</label>
                                        <textarea name="content" class="form-control" rows="3"><?php echo htmlspecialchars($row['content']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="show_email" id="show_email_<?php echo $row['id']; ?>" 
                                                   <?php echo $row['show_email'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="show_email_<?php echo $row['id']; ?>">
                                                顯示 Email
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="show_phone" id="show_phone_<?php echo $row['id']; ?>"
                                                   <?php echo $row['show_phone'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="show_phone_<?php echo $row['id']; ?>">
                                                顯示連絡電話
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" name="edit_message" class="btn btn-primary">儲存</button>
                                    <button type="button" class="btn btn-secondary" onclick="hideEditForm(<?php echo $row['id']; ?>)">取消</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function showEditForm(id) {
        document.getElementById('edit-' + id).style.display = 'table-row';
        document.getElementById('row-' + id).style.display = 'none';
    }

    function hideEditForm(id) {
        document.getElementById('edit-' + id).style.display = 'none';
        document.getElementById('row-' + id).style.display = 'table-row';
    }
    </script>
</body>
</html>