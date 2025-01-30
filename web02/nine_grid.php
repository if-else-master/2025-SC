<?php
session_start();

// 如果沒有通過第一階段驗證，重定向到登入頁面
if (!isset($_SESSION['logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// 生成隨機九宮格數字
if (!isset($_SESSION['grid_numbers'])) {
    $numbers = range(1, 9);
    shuffle($numbers);
    $_SESSION['grid_numbers'] = $numbers;
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>九宮格驗證 - 快樂旅遊網</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            gap: 5px;
            margin: 20px auto;
            width: fit-content;
        }
        .grid-item {
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background-color: #fff;
            cursor: move;
            user-select: none;
        }
        .grid-item.selected {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>第二階段驗證</h2>
        <p>請拖曳數字，將其排列為 1-9 的順序（由左至右，由上至下）</p>
        
        <div class="grid-container" id="grid">
            <?php foreach ($_SESSION['grid_numbers'] as $number): ?>
                <div class="grid-item" draggable="true" data-number="<?php echo $number; ?>">
                    <?php echo $number; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <button id="verify-btn" class="btn btn-primary">確認</button>
        </div>
    </div>

    <script>
        let selectedItem = null;

        document.querySelectorAll('.grid-item').forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragend', handleDragEnd);
        });

        function handleDragStart(e) {
            selectedItem = this;
            this.classList.add('selected');
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();
            if (this !== selectedItem) {
                const allItems = [...document.querySelectorAll('.grid-item')];
                const selectedIndex = allItems.indexOf(selectedItem);
                const dropIndex = allItems.indexOf(this);
                
                // 交換數字
                const temp = selectedItem.textContent;
                selectedItem.textContent = this.textContent;
                this.textContent = temp;
                
                // 交換 data-number
                const tempNumber = selectedItem.dataset.number;
                selectedItem.dataset.number = this.dataset.number;
                this.dataset.number = tempNumber;
            }
        }

        function handleDragEnd() {
            this.classList.remove('selected');
        }

        document.getElementById('verify-btn').addEventListener('click', function() {
            const numbers = [...document.querySelectorAll('.grid-item')]
                .map(item => parseInt(item.dataset.number));
            
            // 檢查是否按照順序排列
            const isCorrect = numbers.every((num, index) => num === index + 1);
            
            if (isCorrect) {
                fetch('verify_grid.php', {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'admin_panel.php';
                    }
                });
            } else {
                alert('順序不正確，請重試！');
            }
        });
    </script>
</body>
</html>