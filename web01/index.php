<!DOCTYPE html>
<html lang="zh-Hant">   
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>網頁首頁</title>
</head>
<body>
    <div class="navbar">
        <div class="main-nav">     
            <a href="index.php">快樂旅遊網</a>        
            <a href="rootmsg.php">訪客留言</a>
            <a href="#">訪客訂房</a>
            <a href="#">訪客訂餐</a>
            <a href="login.php">網站管理</a>
        </div>
        <div class="dropdown">
            <div class="hamburger" onclick="toggleDropdown()">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="dropdown-content" id="dropdownMenu">
                <a href="index.php">快樂旅遊網</a>
                <a href="#">訪客留言</a>
                <a href="#">訪客訂房</a>
                <a href="#">訪客訂餐</a>
                <a href="login.php">網站管理</a>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        // 點擊下拉選單外部時關閉選單
        window.onclick = function(event) {
            if (!event.target.matches('.hamburger') && 
                !event.target.matches('.hamburger span')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>