<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'admin' && $password === 'abcd1234') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = '帳號或密碼錯誤';
        header('Location: login.php');
        exit;
    }
}

header('Location: login.php');
exit;