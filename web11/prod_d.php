<?php
include 'db.php';

$id = null; // 預先定義 $id 變數，避免未定義錯誤
$result2 = null; // 預先定義 $result2 變數，避免未定義錯誤

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search_gtin'])) {
    $gtin = $_POST['gtin'];

    // 檢查是否支援 FULLTEXT，若不支援請改為 `WHERE gtin = ?`
    $sql = "SELECT * FROM prod WHERE gtin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $gtin); // 's' 表示 string
    $stmt->execute();
    $result = $stmt->get_result();

    // 取得查詢結果
    if ($row = $result->fetch_assoc()) {
        $id = $row['comp_id']; // 修正錯誤的欄位名稱
    } else {
        $id = null; // 未找到結果時設為 null
    }
    $stmt->close();

    if ($id !== null) {
        $sql2 = "SELECT * FROM comp WHERE id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param('i', $id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $stmt2->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTIN 查詢</title>
</head>
<body>
    <form method="post">
        <input type="text" name="gtin" placeholder="輸入 GTIN" required>
        <button type="submit" name="search_gtin">查詢</button>
    </form>

    <?php if ($result2 && $result2->num_rows > 0): ?>
        <?php while ($row = $result2->fetch_assoc()): ?>
            <h1><?= $row['name'] ?></h1>            
        <?php endwhile; ?>
    <?php elseif ($id === null): ?>
        <p>未找到結果</p>
    <?php endif; ?>

    <a href="index.php">回首頁</a>
</body>
</html>
