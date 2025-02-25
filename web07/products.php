<?php
include("db.php");

$company_id = $_GET['company_id'];

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])){
    $name = $_POST['name'];
    $name_en = $_POST['name_en'];
    $gtin = $_POST['gtin'];
    $des = $_POST['des'];
    $des_en = $_POST['des_en'];

    if(strlen($gtin)!=13 || !ctype_digit($gtin)){
        echo'無效的gtin碼';
    }


    $image_path = 'uploads/2021.png';
    if(isset($_FILES['image'])){
        if($_FILES['image']['error'] !== UPLOAD_ERR_OK){
            echo'檔案上傳錯誤';
        }

        $upload_dir = 'uploads/';

        $image_name = basename($_FILES['image']['name']);
        $image_path = $upload_dir . $image_name;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $image_path)){
            echo '上傳成功';
        }else{
            echo '上傳失敗';
        }
    }else{
        echo '上傳失敗2';
    }


    $sql = "INSERT INTO `products`(`company_id`, `name`, `name_en`, `gtin`, `description`, `description_en`, `image_path`) VALUES (?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo "資料準備失敗";
    }

    $stmt->bind_param("issssss",$company_id,$name,$name_en,$gtin,$des,$des_en,$image_path);
    if($stmt->execute()){
        echo"產品新增成功";
    }else{
        echo "失敗";
    }

}

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
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="產品名稱(中文)" required>
        <input type="text" name="name_en" placeholder="名稱(英文)" required>
        <input type="text" name="gtin" placeholder="gitn" required>
        <textarea name="des" placeholder="產品描述" required></textarea>
        <textarea name="des_en" placeholder="產品描述(英文)" required></textarea>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add_product">新增</button>
    </form>
</body>
</html>
