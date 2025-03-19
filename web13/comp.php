<?php
include 'db.php';

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_comp'])){
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $owner_name = $_POST['owner_name'];

    $sql = "INSERT INTO `comp`(`name`, `address`, `phone`, `email`, `owner_name`) VALUES (?,?,?,?,?)";
    $stut = $conn->prepare($sql);
    $stut->bind_param('sssss',$name,$address,$phone,$email,$owner_name);
    $stut->execute();
}

if(isset($_GET['action']) && isset($_GET['id'])){
    $id = $_GET['id'];
    if($_GET['action']=='Invalid'){
        $new_status = 'Invalid';
    }elseif($_GET['action']=='valid'){
        $new_status = 'valid';
    }

    $sql = "UPDATE comp SET status = ? where id = ?";
    $stut = $conn->prepare($sql);
    $stut->bind_param('si',$new_status,$id);
    $stut->execute();
}

$sql = "SELECT * FROM `comp`";
$result = $conn->query($sql)
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/comp.css">
</head>
<body>
    <div class="cont">
        <div class="header">
            <div class="logo">LOGO</div>
            <div class="title">管理</div>
            <a href="login.php">登出</a>            
        </div>
        <h2>新增公司</h2>
        <div class="in">
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="公司名稱" required>
                <input type="text" name="address" placeholder="公司地址" required>
                <input type="text" name="phone" placeholder="公司電話" required>
                <input type="text" name="email" placeholder="公司電子郵件" required>
                <input type="text" name="owner_name" placeholder="公司負責人" required>
                <button type="submit" name="add_comp" class="button">新增</button>
            </form>
        </div>
        <div class="padd"></div>
        <table>
            <tr>
                <td>公司名稱</td>
                <td>公司地址</td>
                <td>公司電話</td>
                <td>公司電子郵件</td>
                <td>公司負責人</td>
                <td>狀態</td>
                <td>控制</td>
            </tr>
            <?php while($row = $result->fetch_assoc()):?>
                <tr>
                    <th><a href="prod.php?comp_id=<?= $row['id']?>" style="text-decoration: none;"><?= $row['name']?></a></th>
                    <th><?= $row['address']?></th>
                    <th><?= $row['phone']?></th>
                    <th><?= $row['email']?></th>
                    <th><?= $row['owner_name']?></th>
                    <th><?= $row['status']?></th>
                    <th>
                        <?php if($row['status'] == 'valid'):?>
                            <a href="?action=Invalid&id=<?= $row['id']?>" style="text-decoration: none;">Invalid</a>
                        <?php elseif($row['status'] == 'Invalid'):?>
                            <a href="?action=valid&id=<?= $row['id']?>" style="text-decoration: none;">valid</a>
                        <?php endif;?>
                    </th>
                </tr>
            <?php endwhile;?>
        </table>        
    </div>
    <div class="footer">
        <p>sdfsdf</p>
    </div>
</body>
</html>
</body>
</html>