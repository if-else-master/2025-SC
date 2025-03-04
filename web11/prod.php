<?php
include 'db.php';
$comp_id = $_GET['comp_id'];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_pord'])) {
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $des = $_POST['des'];
    $des_en = $_POST['des_en'];

    if (strlen($gtin) != 13) {
        echo '無效的GTIN';
        exit();
    }

    $image_path = 'uploads/aa.png'; # 預設圖片
    $upload_dir = 'uploads/';

    if (!empty($_FILES['image']['name'])) {
        $image_path = $upload_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $sql = "INSERT INTO `prod`(`comp_id`, `name`, `name_en`, `gtin`, `des`, `des_en`, `image_path`) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssss', $comp_id, $name, $name_en, $gtin, $des, $des_en, $image_path);
    $stmt->execute();
}

if (isset($_GET['action2']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_GET['action2'] == 'visible') {
        $new_status = 'visible';
    } else {
        $new_status = 'hidden';
    }

    $sql = "UPDATE prod SET status = ? WhERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $new_status, $id);
    $stmt->execute();
}

$sql = 'SELECT * FROM comp WHERE id = ? ';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $comp_id);
$stmt->execute();
$result = $stmt->get_result();

$sql = 'SELECT * FROM prod WHERE comp_id = ? ';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $comp_id);
$stmt->execute();
$result2 = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="產品名稱" required>
        <input type="text" name="name_en" placeholder="產品英文名稱" required>
        <input type="text" name="gtin" placeholder="產品GTIN碼" required>
        <textarea name="des" placeholder="產品說明" required></textarea>
        <textarea name="des_en" placeholder="產品英文說明" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add_pord">新增</button>

    </form>

    <table>
        <tr>
            <th>產品名稱</th>
            <th>產品英文名稱</th>
            <th>gtin</th>
            <th>圖片</th>
            <th>產品狀態</th>
            <th>產品狀態控制</th>
        </tr>
        <?php while ($row = $result2->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['name_en'] ?></td>
                <td><?= $row['gtin'] ?></td>
                <td>
                    <?php if (file_exists($row['image_path'])): ?>
                        <img src="<?= $row['image_path'] ?>" style="max-width:100px;">
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    if ($row['status'] == 'visible') {
                        echo '產品可見';
                    } else {
                        echo '產品隱藏';
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['status'] == 'visible'): ?>
                        <a href="?comp_id=<?= $comp_id ?>&action2=hidden&id=<?= $row['id'] ?>">隱藏</a>
                    <?php
                    else:
                    ?>
                        <a href="?comp_id=<?= $comp_id ?>&action2=visible&id=<?= $row['id'] ?>">顯示</a>
                    <?php
                    endif;
                    ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="comp.php">回上一頁</a>
</body>

</html>