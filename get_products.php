<?php
ob_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once 'db.php';

try {
    $sql = "SELECT id, name, price, description, image FROM products ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ob_clean();
    echo json_encode($products, JSON_UNESCAPED_UNICODE);
    exit;

} catch (PDOException $e) {
    error_log("Database Error in get_products.php: " . $e->getMessage());
    http_response_code(500);
    ob_clean();
    echo json_encode([
        "error" => true,
        "message" => "Database error occurred."
    ]);
    exit;
}
?>