<?php
include 'db.php';

$comp_id = $_GET['comp_id'];

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
    <table>
        <tr>
            <th>產品名稱</th>
            <th>產品英文名稱</th>
            <th>gtin</th>
            <th>說明</th>
            <th>英文說明</th>
            <th>圖片</th>
        </tr>
        <?php while ($row = $result2->fetch_assoc()): ?>
            <tr>
                <?php if ($row['status'] == 'hidden'): ?>
                    <td>產品被隱藏</td>
                <?php else: ?>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['name_en'] ?></td>
                    <td><?= $row['gtin'] ?></td>
                    <td><?= $row['des'] ?></td>
                    <td><?= $row['des_en'] ?></td>
                    <td>
                        <?php if (file_exists($row['image_path'])): ?>
                            <img src="<?= $row['image_path'] ?>" style="max-width:100px;">
                        <?php endif; ?>
                    </td>
                <?php endif ?>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">回上一頁</a>
</body>

</html>