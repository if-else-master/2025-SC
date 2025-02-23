<?php
require_once 'includes/db.php';
require_once 'includes/language.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gtin_list = explode("\n", trim($_POST['gtin_list']));
    $results = [];
    $all_valid = true;

    foreach ($gtin_list as $gtin) {
        $gtin = trim($gtin);
        if (empty($gtin)) continue;

        // 验证 GTIN 格式
        if (!preg_match('/^\d{13}$/', $gtin)) {
            $results[] = [
                'gtin' => $gtin,
                'valid' => false,
                'message' => 'Invalid format'
            ];
            $all_valid = false;
            continue;
        }

        // 修改消息部分
        if (!preg_match('/^\d{13}$/', $gtin)) {
            $results[] = [
                'gtin' => $gtin,
                'valid' => false,
                'message' => __('invalid_format')
            ];
            $all_valid = false;
            continue;
        }

        // 查询数据库
        $stmt = $pdo->prepare("
            SELECT p.*, c.is_active 
            FROM products p 
            JOIN companies c ON p.company_id = c.id 
            WHERE p.gtin = ? AND p.is_hidden = 0 AND c.is_active = 1
        ");
        $stmt->execute([$gtin]);
        $product = $stmt->fetch();

        if ($product) {
            $results[] = [
                'gtin' => $gtin,
                'valid' => true,
                'message' => __('valid')
            ];
        } else {
            $results[] = [
                'gtin' => $gtin,
                'valid' => false,
                'message' => __('invalid')
            ];
            $all_valid = false;
        }
    }
?>
<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo __('verify_result'); ?></title>
    <link rel="stylesheet" href="css/unified-style.css">
    <style>
        .verify-result {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .result-item {
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
        }
        .valid {
            background: #d4edda;
            color: #155724;
        }
        .invalid {
            background: #f8d7da;
            color: #721c24;
        }
        .all-valid {
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
            background: #d4edda;
            color: #155724;
            border-radius: 4px;
        }
        .check-icon {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="verify-result">
        <div class="language-switcher">
            <a href="?lang=zh_TW">中文</a> |
            <a href="?lang=en_US">English</a>
        </div>
        
        <?php if ($all_valid && count($results) > 0): ?>
        <div class="all-valid">
            <span class="check-icon">✓</span> <?php echo __('all_valid'); ?>
        </div>
        <?php endif; ?>

        <h2><?php echo __('verify_result'); ?>：</h2>
        <?php foreach ($results as $result): ?>
        <div class="result-item <?php echo $result['valid'] ? 'valid' : 'invalid'; ?>">
            GTIN: <?php echo htmlspecialchars($result['gtin']); ?> - 
            <?php echo $result['message']; ?>
        </div>
        <?php endforeach; ?>

        <div style="margin-top: 20px;">
            <a href="gtin-verify.php" class="btn"><?php echo __('back_to_verify'); ?></a>
            <a href="index.php" class="btn"><?php echo __('back_to_home'); ?></a>
        </div>
    </div>
</body>
</html>
<?php
    exit;
}

header('Location: gtin-verify.php');
exit;
?>