<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// 資料庫連接配置
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'web05';

// 建立資料庫連接
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(['error' => '資料庫連接失敗：' . $conn->connect_error]));
}

$conn->set_charset('utf8mb4');

// 獲取請求方法和路徑
$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? $_GET['path'] : '';

// 解析請求體
$input = json_decode(file_get_contents('php://input'), true);

// API路由處理
switch ($path) {
    // 管理員登入
    case 'login':
        if ($method === 'POST') {
            $username = $input['username'] ?? '';
            $password = $input['password'] ?? '';
            
            if ($username === 'admin' && $password === 'abcd1234') {
                echo json_encode(['success' => true, 'message' => '登入成功']);
            } else {
                echo json_encode(['success' => false, 'message' => '帳號或密碼錯誤']);
            }
        }
        break;

    // 會員公司管理
    case 'companies':
        switch ($method) {
            case 'GET':
                // 獲取所有會員公司
                $status = isset($_GET['status']) ? $_GET['status'] : 'active';
                $sql = "SELECT * FROM companies WHERE status = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $status);
                $stmt->execute();
                $result = $stmt->get_result();
                $companies = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode(['success' => true, 'data' => $companies]);
                break;

            case 'POST':
                // 新增會員公司
                $sql = "INSERT INTO companies (name, address, phone, email, owner_name) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssss', 
                    $input['name'],
                    $input['address'],
                    $input['phone'],
                    $input['email'],
                    $input['owner_name']
                );
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => '新增成功']);
                } else {
                    echo json_encode(['success' => false, 'message' => '新增失敗']);
                }
                break;

            case 'PUT':
                // 更新會員公司
                $sql = "UPDATE companies SET name = ?, address = ?, phone = ?, email = ?, owner_name = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssssi',
                    $input['name'],
                    $input['address'],
                    $input['phone'],
                    $input['email'],
                    $input['owner_name'],
                    $input['id']
                );
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => '更新成功']);
                } else {
                    echo json_encode(['success' => false, 'message' => '更新失敗']);
                }
                break;

            case 'DELETE':
                // 刪除會員公司
                $sql = "DELETE FROM companies WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $input['id']);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => '刪除成功']);
                } else {
                    echo json_encode(['success' => false, 'message' => '刪除失敗']);
                }
                break;
        }
        break;

    // 產品管理
    case 'products':
        switch ($method) {
            case 'GET':
                // 獲取產品列表或單個產品
                if (isset($_GET['gtin'])) {
                    // 根據GTIN查詢產品
                    $sql = "SELECT p.*, c.name as company_name FROM products p 
                            JOIN companies c ON p.company_id = c.id 
                            WHERE p.gtin = ? AND p.status = 'active' AND c.status = 'active'";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $_GET['gtin']);
                } else if (isset($_GET['company_id'])) {
                    // 獲取指定公司的產品
                    $sql = "SELECT * FROM products WHERE company_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $_GET['company_id']);
                } else {
                    // 獲取所有產品
                    $sql = "SELECT p.*, c.name as company_name FROM products p 
                            JOIN companies c ON p.company_id = c.id 
                            WHERE p.status = 'active' AND c.status = 'active'";
                    $stmt = $conn->prepare($sql);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $products = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode(['success' => true, 'data' => $products]);
                break;

            case 'POST':
                // 新增產品
                if (!validateGTIN($input['gtin'])) {
                    echo json_encode(['success' => false, 'message' => 'GTIN格式錯誤']);
                    break;
                }

                $sql = "INSERT INTO products (company_id, name_zh, name_en, gtin, description_zh, description_en, image_path) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('issssss',
                    $input['company_id'],
                    $input['name_zh'],
                    $input['name_en'],
                    $input['gtin'],
                    $input['description_zh'],
                    $input['description_en'],
                    $input['image_path']
                );
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => '新增成功']);
                } else {
                    echo json_encode(['success' => false, 'message' => '新增失敗']);
                }
                break;

            case 'PUT':
                // 更新產品
                if (isset($input['gtin']) && !validateGTIN($input['gtin'])) {
                    echo json_encode(['success' => false, 'message' => 'GTIN格式錯誤']);
                    break;
                }

                $sql = "UPDATE products SET name_zh = ?, name_en = ?, gtin = ?, 
                        description_zh = ?, description_en = ?, image_path = ? 
                        WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssssi',
                    $input['name_zh'],
                    $input['name_en'],
                    $input['gtin'],
                    $input['description_zh'],
                    $input['description_en'],
                    $input['image_path'],
                    $input['id']
                );
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => '更新成功']);
                } else {
                    echo json_encode(['success' => false, 'message' => '更新失敗']);
                }
                break;

            case 'DELETE':
                // 刪除產品
                $sql = "DELETE FROM products WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $input['id']);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => '刪除成功']);
                } else {
                    echo json_encode(['success' => false, 'message' => '刪除失敗']);
                }
                break;
        }
        break;

    // GTIN批次驗證
    case 'validate-gtin':
        if ($method === 'POST') {
            $gtins = $input['gtins'] ?? [];
            $results = [];
            
            foreach ($gtins as $gtin) {
                if (!validateGTIN($gtin)) {
                    $results[$gtin] = 'Invalid';
                    continue;
                }

                $sql = "SELECT COUNT(*) as count FROM products p 
                        JOIN companies c ON p.company_id = c.id 
                        WHERE p.gtin = ? AND p.status = 'active' AND c.status = 'active'";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $gtin);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                
                $results[$gtin] = $result['count'] > 0 ? 'valid' : 'Invalid';
            }

            $allValid = !in_array('Invalid', $results);
            echo json_encode([
                'success' => true,
                'all_valid' => $allValid,
                'results' => $results
            ]);
        }
        break;

    // 匯入/匯出產品資料
    case 'products/import':
        if ($method === 'POST') {
            $company_id = $input['company_id'];
            $products = $input['products'];
            $success = true;
            $conn->begin_transaction();

            try {
                foreach ($products as $product) {
                    if (!validateGTIN($product['gtin'])) {
                        throw new Exception('GTIN格式錯誤：' . $product['gtin']);
                    }

                    $sql = "INSERT INTO products (company_id, name_zh, name_en, gtin, description_zh, description_en) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('isssss',
                        $company_id,
                        $product['name_zh'],
                        $product['name_en'],
                        $product['gtin'],
                        $product['description_zh'],
                        $product['description_en']
                    );
                    if (!$stmt->execute()) {
                        throw new Exception('產品匯入失敗');
                    }
                }
                $conn->commit();
                echo json_encode(['success' => true, 'message' => '匯入成功']);
            } catch (Exception $e) {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'products/export':
        if ($method === 'GET') {
            $company_id = $_GET['company_id'];
            $format = $_GET['format'] ?? 'json';

            $sql = "SELECT * FROM products WHERE company_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $company_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $products = $result->fetch_all(MYSQLI_ASSOC);

            if ($format === 'csv') {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="products.csv"');
                $output = fopen('php://output', 'w');
                fputcsv($output, array_keys($products[0]));
                foreach ($products as $product) {
                    fputcsv($output, $product);
                }
                fclose($output);
            } else {
                echo json_encode(['success' => true, 'data' => $products]);
            }
        }
        break;

    default:
        echo json_encode(['error' => '無效的API路徑']);
        break;
}

// GTIN驗證函數
function validateGTIN($gtin) {
    // 檢查是否為13位數字
    if (!preg_match('/^\d{13}$/', $gtin)) {
        return false;
    }

    // 計算校驗碼
    $sum = 0;
    for ($i = 0; $i < 12; $i++) {
        $digit = intval($gtin[$i]);
        $sum += $digit * ($i % 2 === 0 ? 1 : 3);
    }
    $checksum = (10 - ($sum % 10)) % 10;

    // 驗證校驗碼
    return intval($gtin[12]) === $checksum;
}

$conn->close();
?>