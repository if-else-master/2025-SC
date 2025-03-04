<?php
include 'db.php';

$sql = "SELECT * FROM comp";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="cont">
        <div class="header">
            <div class="logo">LOGO</div>
            <div class="title">title</div>
            <a href="login.php">管理</a>
            <a href="prod_d.php">產品管理列表</a>
        </div>
        <div>
            <div>圖片</div>
            <div>圖片</div>
            <div>圖片</div>
        </div>
        <div>
            <table>
                <tr>
                    <th>公司名稱</th>
                    <th>公司地址</th>
                    <th>公司電話</th>
                    <th>電子郵件</th>
                    <th>擁有者</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <?php if ($row['status'] == 'inactive'): ?>
                            <td>停用</td>
                        <?php else: ?>
                            <td><a href="userread.php?comp_id=<?= $row['id'] ?>"><?= $row['name'] ?></a></td>
                            <td><?= $row['address'] ?></td>
                            <td><?= $row['phone'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['owner_name'] ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <div class="footer">
            <p>sfjsdfjs</p>
            <p>skdfjlksdfjkls</p>
        </div>
    </div>
</body>

</html>