<?php
session_start();

function generateNumericCaptcha($length = 4) {
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha .= rand(0, 9);
    }
    return $captcha;
}

$_SESSION['captcha'] = generateNumericCaptcha(4);
echo $_SESSION['captcha'];
?>