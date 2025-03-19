<?php
include 'db.php';
$list_gtin = []; 
$products = []; // 存查詢結果

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['seach'])) {
    $gtin = $_POST['gtin'];
    $clean = preg_replace('/\s+/', '', $gtin);
    $list_gtin = str_split($clean, 13);

    foreach ($list_gtin as $gtins) {
        $sql = "SELECT * FROM `prod` WHERE gtin = '$gtins'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
}
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
    <div class="cont">
        <div class="header">
            <div class="logo">LOGO</div>
            <div class="title">產品管理</div>
            <a href="index.php">返回</a>            
        </div>
        <h2>查詢產品</h2>
        <form action="#" method="POST">
            <textarea name="gtin" placeholder="GTIN" required></textarea>
            <button type="submit" name="seach">查詢</button>
        </form>
        
        <h2><?= !empty($list_gtin) ? implode(", ", $list_gtin) : "無結果" ?></h2>

        <?php if (!empty($products)): ?>
            <h2>查詢結果：</h2>
            <ul>
                <?php foreach ($products as $product): ?>
                    <li><?= $product['name'] ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h2>查無產品</h2>
        <?php endif; ?>
    </div>
    <div class="footer">
        <p>sdfsdf</p>
    </div>
</body>
</html>
