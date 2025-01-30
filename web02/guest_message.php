<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message_code = $_POST['message_code'];
    
    // 驗證留言序號格式
    if (!preg_match('/^[0-9a-z]{3}$/', $message_code)) {
        $error_message = "留言序號必須是3位數字或小寫英文字母的組合";
    }
    // 檢查留言序號是否已存在
    else {
        $check_sql = "SELECT id FROM messages WHERE message_code = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $message_code);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = "此留言序號已被使用，請選擇其他序號";
        } else {
            // Email 格式驗證
            if (substr_count($email, '@') !== 1 || substr_count($email, '.') < 1) {
                $error_message = "Email 格式錯誤，必須包含一個「@」和至少一個「.」";
            }
            // 電話號碼驗證
            else if (!preg_match('/^[0-9-]+$/', $phone)) {
                $error_message = "電話號碼只能包含數字和減號(-)";
            } 
            else {
                $content = $_POST['content'];
                $created_at = date('Y-m-d H:i:s');
                $image_path = null;
                
                // 處理圖片上傳
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/';
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                
                    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $new_filename = uniqid() . '.' . $file_extension;
                    $upload_path = $upload_dir . $new_filename;
                
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        $image_path = $upload_path;
                    }
                }
                
                $sql = "INSERT INTO messages (name, email, phone, content, created_at, image_path, message_code) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $name, $email, $phone, $content, $created_at, $image_path, $message_code);
                
                if ($stmt->execute()) {
                    $success_message = "留言發表成功！";
                } else {
                    $error_message = "留言發表失敗，請稍後再試。";
                }
            }
        }
    }
}

// 獲取所有留言，置頂的優先顯示
// 修改查詢，不顯示被隱藏的留言（前台）
// 修改查詢語句，加入回應資訊
$sql = "SELECT m.*, GROUP_CONCAT(
            CONCAT(r.reply_content, '|', r.created_at)
            ORDER BY r.created_at ASC
            SEPARATOR '||'
        ) as replies 
        FROM messages m 
        LEFT JOIN message_replies r ON m.id = r.message_id 
        WHERE m.is_hidden = FALSE 
        GROUP BY m.id 
        ORDER BY m.is_pinned DESC, m.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訪客留言 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .message-image {
            max-width: 300px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>訪客留言</h2>
            <div>
                <a href="user_login.php" class="btn btn-outline-primary me-2">登入管理留言</a>
                <a href="add_message.php" class="btn btn-primary">新增留言</a>
            </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">留言發表成功！</div>
        <?php endif; ?>

        <!-- 留言列表 -->
        <div class="messages">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="card mb-3 <?php echo $row['is_pinned'] ? 'border-warning' : ''; ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <?php if ($row['is_pinned']): ?>
                                    <div class="badge bg-warning text-dark mb-2">置頂</div>
                                <?php endif; ?>
                                <?php if (!empty($row['replies'])): ?>
                                    <div class="badge bg-info text-white mb-2 ms-1">已回覆</div>
                                <?php endif; ?>
                            </div>
                            <div class="message-code text-muted">
                                <small>序號：<?php echo htmlspecialchars($row['message_code']); ?></small>
                            </div>
                        </div>
                        
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <?php if (!$row['deleted_at']): ?>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                            <?php if ($row['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                     alt="留言圖片" 
                                     class="message-image">
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">此留言已刪除</p>
                        <?php endif; ?>
                        <div class="text-muted">
                            <small>發表於 <?php echo date('Y/m/d H:i:s', strtotime($row['created_at'])); ?></small>
                            <?php if ($row['updated_at']): ?>
                                <br><small>修改於 <?php echo date('Y/m/d H:i:s', strtotime($row['updated_at'])); ?></small>
                            <?php endif; ?>
                            <?php if ($row['deleted_at']): ?>
                                <br><small>刪除於 <?php echo date('Y/m/d H:i:s', strtotime($row['deleted_at'])); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($row['replies'])): ?>
                        <div class="card-footer bg-light mt-3">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person-circle"></i> 管理員回覆：
                            </h6>
                            <?php
                            $replies = explode('||', $row['replies']);
                            foreach ($replies as $reply):
                                if (empty($reply)) continue;
                                list($content, $time) = explode('|', $reply);
                            ?>
                                <div class="admin-reply mb-2 border-start border-primary ps-3">
                                    <div class="reply-content"><?php echo nl2br(htmlspecialchars($content)); ?></div>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> 
                                        回覆於：<?php echo date('Y/m/d H:i:s', strtotime($time)); ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- 在 head 區塊添加 Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // 表單驗證
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
    </script>
</body>
</html>
