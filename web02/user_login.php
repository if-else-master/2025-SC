<?php
require_once 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $message_code = $_POST['message_code'];
    
    $sql = "SELECT id FROM messages WHERE name = ? AND message_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $message_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['user_name'] = $name;
        $_SESSION['message_code'] = $message_code;
        header('Location: user_dashboard.php');
        exit();
    } else {
        $error_message = "姓名或留言序號錯誤";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>使用者登入 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">使用者登入</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        
                        <form method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">姓名</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="message_code" class="form-label">留言序號</label>
                                <input type="text" class="form-control" id="message_code" name="message_code" required>
                            </div>
                            <button type="submit" class="btn btn-primary">登入</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>