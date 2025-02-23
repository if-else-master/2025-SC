<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTIN 批次驗證</title>
    <style>
        .verify-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
        .result.success {
            background: #d4edda;
            color: #155724;
        }
        .result.error {
            background: #f8d7da;
            color: #721c24;
        }
        .check-icon {
            color: #28a745;
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
    <link rel="stylesheet" href="/assets/css/style.css">    
</head>
<body>
    <div class="verify-container">
        <h1>GTIN 批次驗證</h1>
        <form action="verify-process.php" method="POST">
            <div class="form-group">
                <label for="gtin_list">請輸入要驗證的 GTIN 編號（每行一個）：</label>
                <textarea id="gtin_list" name="gtin_list" rows="10" required></textarea>
            </div>
            <button type="submit">驗證</button>
        </form>
        <a href="index.php" class="btn">返回首頁</a>
    </div>
</body>
</html>