<?php
include("db.php");

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_company'])){
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO `companies`(`name`, `address`, `phone`, `email`, `owner_name`) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss",$name,$address,$phone,$email,$owner_name);
    $stmt->execute();
}
$sql = "SELECT * FROM `companies`";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
    <div class="container">
        <h1>管理系統</h1>
        <a href="login.php">登出</a>
    </div>
    <h2>會員公司管理</h2>
    <form method="POST">
        <h3>新增會員管理公司</h3>
        <input type="text" name="name" placeholder="公司名稱" required>
        <input type="text" name="address" placeholder="公司地址" required>
        <input type="text" name="phone" placeholder="公司電話" required>
        <input type="email" name="email" placeholder="公司電子郵件" required>
        <input type="text" name="owner_name" placeholder="擁有者姓名" required>
        <button type="submit" name="add_company">新增</button>
    </form>
    <div class="padding"></div>
    <table>
        <tr>
            <th>公司名稱</th>
            <th>公司地址</th>
            <th>公司電話</th>
            <th>公司電子郵件</th>
            <th>擁有者姓名</th>
            <th>操作</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name']?></td>
                <td><?= $row['address']?></td>
                <td><?= $row['phone']?></td>
                <td><?= $row['email']?></td>
                <td><?= $row['owner_name']?></td>
                <td>
                    <a href="products.php?company_id=<?= $row['id']?>">查看產品</a>
                </td>
            </tr>
            <?php endwhile; ?>
    </table>
</body>
</html>