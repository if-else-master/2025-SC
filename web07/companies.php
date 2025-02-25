<?php
include("db.php");

if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['add_company'])) {
    # code...
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO companies (name, address, phone,email,owner_name) VALUES(?,?,?,?,?)";
    $stmt = $conn->prepare(query: $sql);    
    $stmt->bind_param("sssss", $name, $address, $phone, $email, $owner_name);
    $stmt->execute();
}

if(isset($_GET['action']) && isset($_GET['id'])){
    $id =$_GET['id'];
    $new_status = ($_GET['action']=='deactivate') ? 'inactive' : 'active';

    $sql = "UPDATE companies SET status = ? WHERE id = ?";
    $stmt = $conn->prepare(query:$sql);
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
}

$sql = "SELECT * FROM companies";
$result = $conn->query(query:$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>會員公司管理</title>
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
    <div class="cont">
        <h1>管理系統</h1>
        <button class="login-button" onclick="logout()">登出</button>
    </div>
    <form method="post">
            <h3>新增會員公司</h3>
            <input type="text" name="name" placeholder="公司名稱" required>
            <input type="text" name="address" placeholder="公司地址" required>
            <input type="text" name="phone" placeholder="公司電話" required>
            <input type="text" name="email" placeholder="公司電子郵件" required>
            <input type="text" name="owner_name" placeholder="負責人姓名" required>
            <button type="submit" name="add_company">新增</button>
        </form>

        <h3>會員公司管理</h3>
        <table>
            <tr>
                <th>公司名稱</th>
                <th>地址</th>
                <th>電話</th>
                <th>電子郵件</th>
                <th>擁有者</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <tr>
                    <td><?= $row['name']?></td>
                    <td><?= $row['address']?></td>
                    <td><?= $row['phone']?></td>
                    <td><?= $row['email']?></td>
                    <td><?= $row['owner_name']?></td>
                    <td><?= $row['status']=='active' ? '啟用' : '未啟用'?></td>        
                    <td>            
                        <?php if($row['status']=='active'):?>
                            <a href="?action=deactivate&id=<?= $row['id']?>">停用</a>
                        <?php else:?>
                            <a href="?action=activate&id=<?= $row['id']?>">啟用</a>
                        <?php endif; ?>
                            <a href="products.php?company_id=<?= $row['id']?>">查看產品</a>
                    </td>
                </tr>
            <?php endwhile;?>
        </table>
        <script>
            function logout(){
                window.location.href = "login.php";
            }
        </script>
</body>
</html>

