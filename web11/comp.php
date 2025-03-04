<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_comp'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO `comp`(`name`, `address`, `phone`, `email`, `owner_name`) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $name, $address, $phone, $email, $owner_name);
    $stmt->execute();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_GET['action'] == 'deac') {
        $new_status = 'inactive';
    } else {
        $new_status = 'active';
    }

    $sql = "UPDATE comp SET status = ? WhERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $new_status, $id);
    $stmt->execute();
}

$sql = "SELECT * FROM comp";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>管理系統</h1>
    <a href="login.php">登出</a>

    <h2>會員公司管理</h2>
    <form method="POST">
        <h3>新增會員公司</h3>
        <input type="text" name="name" placeholder="公司名稱" required>
        <input type="text" name="address" placeholder="地址" required>
        <input type="text" name="phone" placeholder="phone" required>
        <input type="email" name="email" placeholder="email" required>
        <input type="text" name="owner_name" placeholder="owner_name" required>
        <button type="submit" name="add_comp">新增</button>
    </form>
    <form method="POST">
        <table>
            <tr>
                <th>公司名稱</th>
                <th>公司地址</th>
                <th>公司電話</th>
                <th>電子郵件</th>
                <th>擁有者</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['address'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['owner_name'] ?></td>
                    <td><?= $row['status'] == 'active' ? '啟用' : '停用' ?></td>
                    <td>
                        <?php
                        if ($row['status'] == 'active'):
                        ?>
                            <a href="?action=deac&id=<?= $row['id'] ?>">停用</a>
                        <?php
                        else:
                        ?>
                            <a href="?action=active&id=<?= $row['id'] ?>">啟用</a>
                        <?php
                        endif;
                        ?>
                        <a href="prod.php?comp_id=<?= $row['id'] ?>">查看產品</a>
                    </td>
                    <td>
                        <a href="update.php?comp_id=<?= $row['id'] ?>">編輯</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </form>
</body>

</html>