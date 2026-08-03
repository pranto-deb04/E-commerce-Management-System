<?php
session_start();
header('Content-Type: application/json');

include 'db.php';

// ইউজার লগইন চেক
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // ১. ইউজারের মূল অর্ডারগুলো নিয়ে আসা
    $stmt = $conn->prepare("SELECT id AS order_id, total_amount, payment_method, created_at AS order_date FROM orders WHERE user_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];

    while ($row = $result->fetch_assoc()) {
        $order_id = $row['order_id'];

        // ২. প্রতিটি অর্ডারের আইটেমগুলো products টেবিলের সাথে JOIN করে নাম ও দাম নিয়ে আসা
        $item_stmt = $conn->prepare("SELECT oi.quantity, oi.price, p.name AS product_name 
                                    FROM order_items oi 
                                    JOIN products p ON oi.product_id = p.id 
                                    WHERE oi.order_id = ?");
        $item_stmt->bind_param("i", $order_id);
        $item_stmt->execute();
        $items_result = $item_stmt->get_result();

        $items = [];
        while ($item = $items_result->fetch_assoc()) {
            $items[] = $item;
        }
        $item_stmt->close();

        // আইটেমের তালিকা অর্ডারের ভেতরে যোগ করা
        $row['items'] = $items;
        $orders[] = $row;
    }

    $stmt->close();

    echo json_encode(["success" => true, "orders" => $orders]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
