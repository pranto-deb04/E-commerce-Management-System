<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'db.php';

try {
    $sql = "
        SELECT 
            o.*,
            COALESCE(u.name, 'Customer') AS customer_name,
            u.email AS customer_email
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC
    ";

    $stmt = $pdo->query($sql);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ob_clean();
    echo json_encode($orders, JSON_UNESCAPED_UNICODE);
    exit;

} catch (PDOException $e) {
    error_log("Database Error in get_all_orders.php: " . $e->getMessage());
    http_response_code(500);
    ob_clean();
    echo json_encode([
        "status"  => "error",
        "message" => "Database Error: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
?>