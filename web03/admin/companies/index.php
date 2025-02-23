<?php
require_once '../auth.php';
require_once '../../includes/db.php';

$active = isset($_GET['status']) && $_GET['status'] === 'inactive' ? 0 : 1;
$stmt = $pdo->prepare("SELECT * FROM companies WHERE is_active = ? ORDER BY name");
$stmt->execute([$active]);
$companies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員公司管理</title>
</head>
<body>
    <div class="admin-container">
        <h1>會員公司管理</h1>
        <div class="actions">
            <a href="create.php" class="btn">新增會員公司</a>
            <a href="?status=<?php echo $active ? 'inactive' : 'active'; ?>" class="btn">
                查看<?php echo $active ? '停用' : '啟用'; ?>公司
            </a>
            <a href="../index.php" class="btn">返回主頁</a>
            <div class="export-buttons" style="margin-top: 10px;">
                <a href="export.php?format=json" class="btn">匯出 JSON</a>
                <a href="export.php?format=csv" class="btn">匯出 CSV</a>            
                <label for="file-upload" class="btn-small">匯入檔案</label>
                <input id="file-upload" type="file" accept=".json,.csv" style="display: none;" onchange="handleFileUpload(this)">
            </div>
        </div>

        <!-- 在 </body> 前添加 JavaScript -->
        <script>
        function handleFileUpload(input) {
            const file = input.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);

            fetch('import.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('檔案匯入成功！');
                    location.reload();
                } else {
                    alert('匯入失敗：' + data.message);
                }
            })
            .catch(error => {
                alert('匯入發生錯誤！');
                console.error(error);
            });
        }
        </script>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>公司名稱</th>
                    <th>電話</th>
                    <th>電子郵件</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?php echo htmlspecialchars($company['name']); ?></td>
                    <td><?php echo htmlspecialchars($company['phone']); ?></td>
                    <td><?php echo htmlspecialchars($company['email']); ?></td>
                    <td>
                        <a href="view.php?id=<?php echo $company['id']; ?>" class="btn-small">檢視</a>
                        <a href="edit.php?id=<?php echo $company['id']; ?>" class="btn-small">編輯</a>
                        <form action="toggle_status.php" method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $company['id']; ?>">
                            <button type="submit" class="btn-small">
                                <?php echo $active ? '停用' : '啟用'; ?>
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