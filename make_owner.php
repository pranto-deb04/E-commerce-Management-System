<?php
require_once 'db.php';

$name = 'Pranto';
$email = 'owner@gmail.com';
$password = '12341234';
$role = 'owner';

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$check_sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $update_sql = "UPDATE users SET password = ?, role = ? WHERE email = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sss", $hashed_password, $role, $email);
    if ($stmt->execute()) {
        echo "<h2 style='color:green;'>Success! Owner Password Updated.</h2>";
    } else {
        echo "<h2 style='color:red;'>Error updating: " . $conn->error . "</h2>";
    }
} else {
    $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    if ($stmt->execute()) {
        echo "<h2 style='color:green;'>Success! Owner Created Successfully.</h2>";
    } else {
        echo "<h2 style='color:red;'>Error inserting: " . $conn->error . "</h2>";
    }
}
?>
