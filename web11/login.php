<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == "admin" && $password == "abcd1234") {
        header("Location:comp.php");
        exit();
    } else {
        echo '帳號密碼錯誤';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/login.php">
</head>

<body>
    <h2>登入</h2>

    <div class="login-c">
        <form action="#" method="POST">
            <div>
                <label for="username">帳號</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">密碼</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="button">登入</button>
            <a href="index.php">回首頁</a>
        </form>
    </div>
</body>

</html>