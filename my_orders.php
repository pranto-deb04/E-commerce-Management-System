<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;

if (!$user_id) {
    ob_clean();
    echo json_encode([
        "success" => false, 
        "message" => "Session user_id is missing. Please log in again."
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC");
    $stmt->execute(['user_id' => $user_id]);
    $result_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $orders = [];

    if (!empty($result_orders)) {
        $item_stmt = $pdo->prepare("
            SELECT oi.*, COALESCE(p.name, 'Product') AS product_name 
            FROM order_items oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = :order_id
        ");

        foreach ($result_orders as $row) {
            $order_id = $row['id'] ?? $row['order_id'] ?? null;

            $items = [];
            if ($order_id) {
                $item_stmt->execute(['order_id' => $order_id]);
                $items_result = $item_stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($items_result as $item) {
                    $items[] = [
                        'product_name' => $item['product_name'],
                        'quantity'     => intval($item['quantity'] ?? 1),
                        'price'        => floatval($item['price'] ?? 0)
                    ];
                }
            }

            $row['items']          = $items;
            $row['order_id']       = $order_id;
            $row['order_date']     = $row['created_at'] ?? $row['order_date'] ?? $row['date'] ?? 'N/A';
            $row['total_amount']   = floatval($row['total_amount'] ?? $row['total'] ?? 0);
            $row['payment_method'] = $row['payment_method'] ?? 'COD';

            $orders[] = $row;
        }
    }

    ob_clean();
    echo json_encode([
        "success" => true, 
        "debug_user_id" => $user_id,
        "orders" => $orders
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (PDOException $e) {
    error_log("Database Error in my_orders.php: " . $e->getMessage());
    ob_clean();
    echo json_encode([
        "success" => false, 
        "message" => "Database error: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
} catch (Exception $e) {
    error_log("General Error in my_orders.php: " . $e->getMessage());
    ob_clean();
    echo json_encode([
        "success" => false, 
        "message" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>