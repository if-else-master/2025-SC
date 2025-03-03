<?php
include "db.php";

if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['add_comp'])){
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO `comps`(`name`, `address`, `phone`, `email`, `owner_name`) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",$name,$address,$phone,$email,$owner_name);
    $stmt->execute();    
}

$sql = "SELECT * FROM `comps`";
$result = $conn->query($sql);
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
        <h1>管理系統</h1>
        <a href="login.php"><button class="button">登出</button></a>
    </div>
    <h2>會員管理公司</h2>
    <form method="post">
        <h3>新增會員管理公司</h3>
        <input type="text" name="name" placeholder="公司名稱" required>
        <input type="text" name="address" placeholder="公司地址" required>
        <input type="text" name="phone" placeholder="公司電話" required>
        <input type="email" name="email" placeholder="公司電子郵件" required>
        <input type="text" name="owner_name" placeholder="公司負責人" required>
        <button type="submit" name="add_comp">新增</button>
    </form>
    <h3>公司會員列表</h3>
    <table>
        <tr>
            <th>公司名稱</th>
            <th>地址</th>
            <th>電話</th>
            <th>郵件</th>
            <th>擁有者姓名</th>
            <th>操嘬</th>
        </tr>
        <?php while($row = $result->fetch_assoc()):?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['owner_name'] ?></td>
            <td>
                <a href="prod.php?comp_id=<?=$row['id']?>">查看產品</a>
            </td>
        </tr>
        <?php endwhile;?>
    </table>
</body>
</html>