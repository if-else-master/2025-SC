<?php
session_start();

// 檢查是否有提交表單
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];

    // 驗證驗證碼
    if ($captcha != $_SESSION['captcha']) {
        $_SESSION['error'] = '驗證碼錯誤';
        header('Location: admin_login.php');
        exit();
    }

    // 驗證帳號密碼
    if ($username === 'admin' && $password === '1234') {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header('Location: nine_grid.php'); // 進入九宮格驗證頁面
        exit();
    } else {
        $_SESSION['error'] = '帳號或密碼錯誤';
        header('Location: admin_login.php');
        exit();
    }
}
?>