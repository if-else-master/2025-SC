<?php
session_start(); 
if(isset($_SESSION["admin_logged_in"]) && $_SESSION['admin_logged_in']===true){
    header(header: "Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理員登入</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="card" style="max-width: 400px; margin:100px auto;">
            <h2 style="text-align: center; margin-bottom: 2rem;">管理員登入</h2>

            <?php if(isset($_SESSION['error'])):?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']);?>
                </div>
            <?php endif;?>

            <form action="process_login.php" method="POST">
                <div class="form-group">
                    <label for="username">帳號</label>
                    <input type="text" name="username" id="username" class="form-control" required>                    
                </div>
                <div class="form-group">
                    <label for="password">密碼</label>
                    <input type="password" name="password" id="password" class="form-control" required>">
                </div>
                <div style="display: flex; gap:10px;">
                    <button>登入</button>
                    <a href="../index.php">回首頁</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>