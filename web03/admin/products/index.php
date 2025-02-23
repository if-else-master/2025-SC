<?php
require_once '../auth.php';
require_once '../../includes/db.php';

$stmt = $pdo->query("
    SELECT p.*, c.name as company_name 
    FROM products p 
    JOIN companies c ON p.company_id = c.id 
    ORDER BY c.name, p.name_zh
");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>產品管理</title>
  
</head>
<body>
    <div class="admin-container">
        <h1>產品管理</h1>
        <div class="actions">
            <a href="create.php" class="btn">新增產品</a>
            <!-- <a href="import.php" class="btn">批次匯入</a>
            <a href="export.php" class="btn">匯出資料</a> -->
            <a href="../index.php" class="btn">返回主頁</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>公司名稱</th>
                    <th>產品名稱(中)</th>
                    <th>產品名稱(英)</th>
                    <th>GTIN</th>
                    <th>狀態</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['name_zh']); ?></td>
                    <td><?php echo htmlspecialchars($product['name_en']); ?></td>
                    <td><?php echo htmlspecialchars($product['gtin']); ?></td>
                    <td><?php echo $product['is_hidden'] ? '隱藏' : '顯示'; ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $product['id']; ?>" class="btn-small">檢視</a>
                        <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn-small">編輯</a>
                        <form action="toggle_status.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn-small">
                                <?php echo $product['is_hidden'] ? '顯示' : '隱藏'; ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>