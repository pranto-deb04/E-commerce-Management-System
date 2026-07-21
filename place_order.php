<?php
// আপনার ডাটাবেজ কানেকশন ফাইল
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ফর্ম থেকে আসা ডেটা রিসিভ করা
    $full_name      = $_POST['fullname'] ?? '';
    $phone          = $_POST['phone'] ?? '';
    $email          = $_POST['email'] ?? '';
    $address        = $_POST['address'] ?? '';
    $city           = $_POST['city'] ?? '';
    $zip            = $_POST['zip'] ?? '';
    $payment_method = $_POST['payment'] ?? 'COD';
    $total_amount   = 75.00; // আপনার অর্ডারের টোটাল প্রাইস

    if (empty($full_name) || empty($phone) || empty($address)) {
        echo json_encode(["status" => "error", "message" => "Required fields missing"]);
        exit;
    }

    // SQL Injection থেকে বাঁচতে Prepared Statement
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, email, address, city, zip, total_amount, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssds", $full_name, $phone, $email, $address, $city, $zip, $total_amount, $payment_method);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Order added to database successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save order: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>