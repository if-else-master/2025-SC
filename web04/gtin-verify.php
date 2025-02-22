<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>批次驗證</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .ver-con{
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);            
        }
        .result{
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;            
        }
        .res.suc{
            background: #28a745;
            color: green;
        }
        .result.error{
            background: white;
            color: red;            
        }
        .check-icon{
            color: green;
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div>
        <h1>GTIN 批次驗證</h1>
        <form action="verify-process.php" method="POST">
            <div class="form-group">
                <label for="gtin_list">請輸入要驗證的 GTIN 編號（每行一個）：</label>
                <textarea name="gtin_list" id="gtin_list" rows="10" required></textarea>
            </div>
            <button type="submit">驗證</button>
        </form>        
        <a href="index.php">回首頁</a>
    </div>
</body>
</html>