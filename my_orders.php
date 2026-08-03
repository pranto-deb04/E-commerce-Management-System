<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false, 
        "message" => "Session user_id is missing. Please log in again."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
    if (!$stmt) {
        throw new Exception("Orders Query Prepare Failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $order_id = $row['id'] ?? $row['order_id'] ?? null;

        if ($order_id) {
            $item_stmt = $conn->prepare("SELECT oi.*, COALESCE(p.name, 'Product') AS product_name 
                                        FROM order_items oi 
                                        LEFT JOIN products p ON oi.product_id = p.id 
                                        WHERE oi.order_id = ?");
            
            if ($item_stmt) {
                $item_stmt->bind_param("i", $order_id);
                $item_stmt->execute();
                $items_result = $item_stmt->get_result();

                $items = [];
                while ($item = $items_result->fetch_assoc()) {
                    $items[] = [
                        'product_name' => $item['product_name'],
                        'quantity'     => $item['quantity'] ?? 1,
                        'price'        => $item['price'] ?? 0
                    ];
                }
                $item_stmt->close();
                $row['items'] = $items;
            }
        }

        $row['order_id']       = $order_id;
        $row['order_date']     = $row['created_at'] ?? $row['order_date'] ?? $row['date'] ?? 'N/A';
        $row['total_amount']   = $row['total_amount'] ?? $row['total'] ?? 0;
        $row['payment_method'] = $row['payment_method'] ?? 'COD';

        $orders[] = $row;
    }

    $stmt->close();

    echo json_encode([
        "success" => true, 
        "debug_user_id" => $user_id,
        "orders" => $orders
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false, 
        "message" => $e->getMessage()
    ]);
}

$conn->close();
?>