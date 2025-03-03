<?php
include 'db.php';

$comp_id = $_GET['comp_id'];
$format = $_GET['format'];


$sql = "SELECT * FROM prods WHERE comp_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$comp_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

if($format == 'csv'){
    header('Content-Type:text/csv');
    header('Content-Disposition:attachment;filename="products.csv');

    $fp = fopen('php://output','w');
    fputcsv($fp,['產品名稱','產品英文名稱','GTIN','產品描述','產品英文描述','狀態']);
    foreach($products as $product){
        fputcsv($fp,[$product['name'],$product['name_en'],$product['gtin'],$product['des'],$product['des_en'],$product['status']]);        
    }
    fclose($fp);
}

if($format =='json'){
    header('Content-Type:application/json');
    header('Content-Disposition:attachement;filename="products.json');
    echo json_encode($products,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>