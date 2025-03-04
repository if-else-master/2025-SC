<?php
include 'db.php';

$comp_id = $_GET['comp_id'];

$sql = 'SELECT * FROM `comp` WHERE id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $comp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_comp'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = 'UPDATE `comp` SET `name`=?,`address`=?,`phone`=?,`email`=?,`owner_name`=? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $address, $phone, $email, $owner_name, $comp_id);
    $stmt->execute();
    header("Location:comp.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php while ($row = $result->fetch_assoc()): ?>
        <form method="POST">
            <h3>新增會員公司</h3>
            <input type="text" name="name" placeholder="<?= $row['name'] ?>" required>
            <input type="text" name="address" placeholder="<?= $row['address'] ?>" required>
            <input type="text" name="phone" placeholder="<?= $row['phone'] ?>" required>
            <input type="email" name="email" placeholder="<?= $row['email'] ?>" required>
            <input type="text" name="owner_name" placeholder="<?= $row['owner_name'] ?>" required>
            <button type="submit" name="update_comp">修改</button>
        </form>
    <?php endwhile; ?>
    <a href="comp.php">回上一頁</a>
</body>

</html>