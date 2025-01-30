<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>第二階段驗證</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 20px;
        }
        .grid-item {
            padding: 20px;
            border: 1px solid #ccc;
            text-align: center;
            cursor: move;
            user-select: none;
        }
        .button-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>第二階段驗證</h2>
        <div class="grid-container" id="grid-container">
            <?php
            $numbers = range(1, 9);
            shuffle($numbers);
            foreach ($numbers as $number) {
                echo "<div class='grid-item' draggable='true' data-value='$number'>$number</div>";
            }
            ?>
        </div>
        <div class="button-container">
            <button type="button" onclick="checkOrder()">確認</button>
        </div>
    </div>

    <script>
        let draggedItem = null;

        document.querySelectorAll('.grid-item').forEach(item => {
            item.addEventListener('dragstart', function(e) {
                draggedItem = this;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            });

            item.addEventListener('dragover', function(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }
                e.dataTransfer.dropEffect = 'move';
                return false;
            });

            item.addEventListener('drop', function(e) {
                if (e.stopPropagation) {
                    e.stopPropagation();
                }
                if (draggedItem != this) {
                    let draggedValue = draggedItem.getAttribute('data-value');
                    let targetValue = this.getAttribute('data-value');
                    draggedItem.setAttribute('data-value', targetValue);
                    this.setAttribute('data-value', draggedValue);
                    draggedItem.innerHTML = targetValue;
                    this.innerHTML = draggedValue;
                }
                return false;
            });
        });

        function checkOrder() {
            let items = document.querySelectorAll('.grid-item');
            let values = Array.from(items).map(item => parseInt(item.getAttribute('data-value')));
            let correctOrder = Array.from({length: 9}, (_, i) => i + 1);
            if (JSON.stringify(values) === JSON.stringify(correctOrder)) {
                window.location.href = 'messages.php';
            } else {
                alert('數字順序不正確，請重新排列。');
            }
        }
    </script>
</body>
</html>
