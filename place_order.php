<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

$user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? null;

if (!$user_id) {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "User not logged in. Please login first."]);
    exit;
}

$raw_input = file_get_contents('php://input');
$input = json_decode($raw_input, true);

if ($input && is_array($input)) {
    $phone          = trim($input['phone'] ?? '');
    $address        = trim($input['address'] ?? '');
    $city           = trim($input['city'] ?? '');
    $zip            = trim($input['zip'] ?? '');
    $payment_method = trim($input['payment'] ?? 'COD');
    $total_amount   = floatval($input['total_amount'] ?? 0);
    $cart           = $input['cart'] ?? [];
} else {
    $phone          = trim($_POST['phone'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $zip            = trim($_POST['zip'] ?? '');
    $payment_method = trim($_POST['payment'] ?? 'COD');
    $total_amount   = floatval($_POST['total_amount'] ?? 0);
    
    $cart_raw       = $_POST['cart_data'] ?? $_POST['cart'] ?? '[]';
    $cart           = is_string($cart_raw) ? json_decode($cart_raw, true) : $cart_raw;
}

if (empty($phone) || empty($address)) {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Required fields missing (Phone or Address)"]);
    exit;
}

if (!is_array($cart) || empty($cart)) {
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Your cart is empty"]);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt1 = $pdo->prepare("INSERT INTO orders (user_id, phone, shipping_address, city, zip, total_amount, payment_method) VALUES (:user_id, :phone, :address, :city, :zip, :total_amount, :payment_method)");
    $stmt1->execute([
        'user_id'        => $user_id,
        'phone'          => $phone,
        'address'        => $address,
        'city'           => $city,
        'zip'            => $zip,
        'total_amount'   => $total_amount,
        'payment_method' => $payment_method
    ]);
    
    $order_id = $pdo->lastInsertId();

    $stmt2 = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
    
    foreach ($cart as $item) {
        $product_id = intval($item['id'] ?? $item['product_id'] ?? 0);
        $quantity   = intval($item['quantity'] ?? 1);
        $price      = floatval($item['price'] ?? 0);

        if ($product_id > 0) {
            $stmt2->execute([
                'order_id'   => $order_id,
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'price'      => $price
            ]);
        }
    }

    $pdo->commit();
    
    ob_clean();
    echo json_encode(["status" => "success", "message" => "Order added to database successfully!", "order_id" => $order_id]);
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Database Error in place_order.php: " . $e->getMessage());
    
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Failed to save order: " . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    ob_clean();
    echo json_encode(["status" => "error", "message" => "Failed to save order: " . $e->getMessage()]);
    exit;
}
?>