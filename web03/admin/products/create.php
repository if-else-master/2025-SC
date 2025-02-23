<?php
require_once '../auth.php';
require_once '../../includes/db.php';

$stmt = $pdo->query("SELECT id, name FROM companies WHERE is_active = 1 ORDER BY name");
$companies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增產品</title>   
</head>
<body>
    <div class="admin-container">
        <h1>新增產品</h1>
        <form action="store.php" method="POST" enctype="multipart/form-data" class="form">
            <div class="form-group">
                <label for="company_id">所屬公司：</label>
                <select id="company_id" name="company_id" required>
                    <option value="">請選擇公司</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo $company['id']; ?>">
                            <?php echo htmlspecialchars($company['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name_zh">產品名稱(中文)：</label>
                <input type="text" id="name_zh" name="name_zh" required>
            </div>
            <div class="form-group">
                <label for="name_en">產品名稱(英文)：</label>
                <input type="text" id="name_en" name="name_en" required>
            </div>
            <div class="form-group">
                <label for="gtin">GTIN：</label>
                <input type="text" id="gtin" name="gtin" pattern="\d{13}" title="請輸入13位數字" required>
            </div>
            <div class="form-group">
                <label for="description_zh">產品描述(中文)：</label>
                <textarea id="description_zh" name="description_zh" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="description_en">產品描述(英文)：</label>
                <textarea id="description_en" name="description_en" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="image">產品圖片：</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <div class="form-actions">
                <button type="submit">儲存</button>
                <a href="index.php" class="btn">返回</a>
            </div>
        </form>
    </div>
</body>
</html>