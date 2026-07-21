<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['admin_name'];
    $email = $_POST['admin_email'];
    $password = $_POST['admin_password'];
    $role = 'admin';

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='admin_management.html';</script>";
    } else {
        $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('New Admin Added Successfully!'); window.location.href='admin_management.html';</script>";
        } else {
            echo "<script>alert('Failed to add admin.'); window.location.href='admin_management.html';</script>";
        }
    }
}
?>