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
                    header('Location: guest_message.php?success=1');
                    exit();
                } else {
                    $error_message = "留言發表失敗，請稍後再試。";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增留言 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>新增留言</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="message_code" class="form-label">留言序號（3位數字或小寫英文字母）</label>
                        <input type="text" class="form-control" id="message_code" name="message_code" 
                               pattern="[0-9a-z]{3}" maxlength="3" required>
                        <div class="invalid-feedback">請輸入3位數字或小寫英文字母</div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">姓名</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">請輸入姓名</div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">請輸入有效的Email</div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">連絡電話</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                        <div class="invalid-feedback">請輸入連絡電話</div>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">留言內容</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                        <div class="invalid-feedback">請輸入留言內容</div>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">上傳圖片（選填）</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">發表留言</button>
                        <a href="guest_message.php" class="btn btn-secondary">返回留言列表</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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