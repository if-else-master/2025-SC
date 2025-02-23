<?php
session_start();
require_once '../inc/db.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if($username === 'admin' && $password === '1234'){
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header(header:'Location: index.php');
        exit;
    }else{
        $_SESSION['error'] = '帳號密碼錯誤';
        header(header:'Location: login.php');
        exit;
    }
}

header(header: 'Location: login.php');
exit;