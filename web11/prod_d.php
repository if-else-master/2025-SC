<?php
include 'db.php';

$id = null; // 預先定義 $id 變數，避免未定義錯誤
$result2 = null; // 預先定義 $result2 變數，避免未定義錯誤
$prod_results = []; // 預先定義 $prod_results 變數，避免未定義錯誤

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search_gtin'])) {
    $gtins = explode("\n", $_POST['gtin']); // 將輸入的 GTIN 分割成數組
    $gtins = array_map('trim', $gtins); // 去除每個 GTIN 的前後空白

    foreach ($gtins as $gtin) {
        if (!empty($gtin)) {
            $sql = "SELECT * FROM prod WHERE gtin = '$gtin'";
            $prod_result = $conn->query($sql);

            // 取得查詢結果
            if ($row = $prod_result->fetch_assoc()) {
                $prod_results[] = $row; // 將結果加入數組
                $id = $row['comp_id']; // 修正錯誤的欄位名稱
            } else {
                $id = null; // 未找到結果時設為 null
            }

            if ($id !== null) {
                $sql2 = "SELECT * FROM comp WHERE id = $id";
                $result2 = $conn->query($sql2);
            }
        }
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
    <div>
        <form method="POST">
            <label for="gtin">GTIN:</label>
            <textarea name="gtin" id="gtin" rows="10" cols="30" required></textarea>
            <button type="submit" name="search_gtin">搜尋</button>
            <a href="index.php">回首頁</a>
        </form>


        <?php foreach ($prod_results as $row): ?>
            <h3><?= $row['gtin'] ?>:<?= $row['status'] ?></h3>
        <?php endforeach; ?>

        <?php if (!empty($prod_results)): ?>
            <?php foreach ($prod_results as $row): ?>
                <img src="<?= $row['image_path'] ?>" style="width: 100px;">
                <h3>GTIN：<?= $row['gtin'] ?></h3>
                <h3>產品資訊：<?= $row['des'] ?></h3>
                <?php while ($comp_row = $result2->fetch_assoc()): ?>
                    <p>公司名稱: <?= $comp_row['name'] ?></p>
                <?php endwhile; ?>
            <?php endforeach; ?>        
        <?php endif; ?>
    </div>
</body>

</html>