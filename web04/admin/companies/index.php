<?php
require_once '../auth.php';
require_once '../../inc/db.php';

$active = isset($_GET['status']) && $_GET['status'] === 'inactive' ? 0 : 1;
$stmt = $pdo->prepare(query: "SELECT * FROM companies WHERE is_active = ? ORDER BY name");
$stmt->execute(params: [$active]);
$companies = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員公司管理</title>
</head>
<body>
    <div>
        <h1>會員公司管理</h1>
        <div>
            <a href="create.php" class="btn">新增會員公司</a>
            <a href="?status=<?echo $active ? 'inactive' : 'active'; ?>">
                查看<?php echo $active ? '停用' : '啟用'; ?>公司
            </a>
            <a href="../index.php" class="btn">返回主頁</a>
        </div>
        <?php if(isset($_SESSION['message'])):?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']);?></div>
        <?php endif;?>
    </div>
</body>
</html>