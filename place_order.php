<?php
session_start();
header('Content-Type: application/json');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in. Please login first."]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $phone          = $input['phone'] ?? '';
    $address        = $input['address'] ?? '';
    $city           = $input['city'] ?? '';
    $zip            = $input['zip'] ?? '';
    $payment_method = $input['payment'] ?? 'COD';
    $total_amount   = $input['total_amount'] ?? 0.00;
    $cart           = $input['cart'] ?? [];
} else {
    $phone          = $_POST['phone'] ?? '';
    $address        = $_POST['address'] ?? '';
    $city           = $_POST['city'] ?? '';
    $zip            = $_POST['zip'] ?? '';
    $payment_method = $_POST['payment'] ?? 'COD';
    $total_amount   = $_POST['total_amount'] ?? 0.00;
    
    $cart_raw       = $_POST['cart_data'] ?? $_POST['cart'] ?? '[]';
    $cart           = json_decode($cart_raw, true);
}

$user_id = $_SESSION['user_id'];

if (empty($phone) || empty($address)) {
    echo json_encode(["status" => "error", "message" => "Required fields missing (Phone or Address)"]);
    exit;
}

try {
    $conn->begin_transaction();

    $stmt1 = $conn->prepare("INSERT INTO orders (user_id, phone, shipping_address, city, zip, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt1->bind_param("issssds", $user_id, $phone, $address, $city, $zip, $total_amount, $payment_method);
    $stmt1->execute();
    
    $order_id = $conn->insert_id;
    $stmt1->close();

    if (!empty($cart) && is_array($cart)) {
        $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        
        foreach ($cart as $item) {
            $product_id = $item['id'] ?? $item['product_id'] ?? 0;
            $quantity   = $item['quantity'] ?? 1;
            $price      = $item['price'] ?? 0.00;

            $stmt2->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt2->execute();
        }
        $stmt2->close();
    }

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Order added to database successfully!", "order_id" => $order_id]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => "Failed to save order: " . $e->getMessage()]);
}

$conn->close();
?>