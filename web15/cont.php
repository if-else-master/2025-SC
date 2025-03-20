<?php
include 'db.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_comp'])){
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO `cont`(`name`, `address`, `phone`, `email`, `owner_name`) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss',$name,$address,$phone,$email,$owner_name);
    $stmt->execute();
}

// $sql = "SELECT * FROM `cont`";
// $result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/cont.css">
</head>
<body>
    <div class="cont">
        <div class="header">
            <div class="logo">LOGO</div>
            <div class="title">title</div>
            <a href="login.php">登出</a>           
        </div>
        <h2>新增產品</h2>
        <div class="in">
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="公司名稱" required>
                <input type="text" name="address" placeholder="公司地址" required>
                <input type="text" name="phone" placeholder="公司電話" required>
                <input type="text" name="email" placeholder="公司郵件" required>
                <input type="text" name="owner_name" placeholder="公司擁有者" required>               
                <button type="submit" class="button" name="add_comp">新增</button>
            </form>
        </div>

        <div class="padding"></div>
        <footer>            
            <p>sdfsfsdfsdf</p>
        </footer>
    </div>
</body>
</html>