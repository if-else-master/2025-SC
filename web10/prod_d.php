<?php
include 'db.php';

$language = $_GET['lang'] ?? 'zh';
$name_field = $language == 'zh' ? 'name' : 'name_en';
$description_field = $language == 'zh' ? 'des' : 'des_en';
$results = $products = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach (explode("\n", trim($_POST['gtins'])) as $gtin) {
        $gtin = trim($gtin);
        if (strlen($gtin) != 13 || !ctype_digit($gtin)) {
            $results[] = ["gtin" => $gtin, "status" => $language == 'zh' ? "格式錯誤" : "Invalid format"];
            continue;
        }
        $stmt = $conn->prepare("SELECT p.*, c.name AS company_name FROM prods p JOIN comps c ON p.comp_id = c.id WHERE p.gtin = ? AND p.status = 'active'");
        $stmt->bind_param("s", $gtin);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $results[] = ["gtin" => $gtin, "status" => "Valid"];
            $products[] = $row;
        } else {
            $results[] = ["gtin" => $gtin, "status" => $language == 'zh' ? "Invalid" : "Invalid"];
        }
        $allValid = true;
        foreach ($results as $result) {
            if ($result["status"] !== "Valid") {
                $allValid = false;
                break;
            }
        }        

    }
}
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $language == 'zh' ? "GTIN 驗證" : "GTIN Validation" ?></title>
</head>
<body>
    <a href="?lang=zh">中文</a> | <a href="?lang=en">English</a>
    <form method="POST">
        <textarea name="gtins" rows="10"></textarea>
        <button type="submit"><?= $language == 'zh' ? "驗證" : "Validate" ?></button>
        <a href="index.html">回首頁</a>
    </form>
    <?php
        if ($allValid) {
            echo "all valid";
        }
    ?>
    <?php foreach ($results as $r): ?>       
        <p>GTIN: <?= $r['gtin'] ?> - <?= $r['status'] ?></p>
    <?php endforeach; ?>
    <h3>產品資訊</h3>
    <?php foreach ($products as $p): ?>
        <div>
            <img src="<?= htmlspecialchars($p['image_path']) ?>" style="max-width:100px">
            <h3><?= htmlspecialchars($p['company_name']) ?></h3>
            <h3><?= htmlspecialchars($p[$name_field]) ?></h3>
            <p>GTIN: <?= htmlspecialchars($p['gtin']) ?></p>
            <p><?= htmlspecialchars($p[$description_field]) ?></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
