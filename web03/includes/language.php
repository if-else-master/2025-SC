<?php
session_start();

function setLanguage($lang) {
    $supported_languages = ['zh_TW', 'en_US'];
    if (in_array($lang, $supported_languages)) {
        $_SESSION['lang'] = $lang;
    }
}

function getCurrentLanguage() {
    return $_SESSION['lang'] ?? 'zh_TW';
}

function __($key) {
    static $translations = null;
    if ($translations === null) {
        $lang = getCurrentLanguage();
        $translations = require __DIR__ . "/../languages/{$lang}.php";
    }
    return $translations[$key] ?? $key;
}

// 处理语言切换
if (isset($_GET['lang'])) {
    setLanguage($_GET['lang']);
    $redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $redirect_url");
    exit;
}