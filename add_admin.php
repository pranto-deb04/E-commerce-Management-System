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
?><?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['admin_name'] ?? '';
    $email    = $_POST['admin_email'] ?? '';
    $password = $_POST['admin_password'] ?? '';
    $role     = 'admin';

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $check_sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $pdo->prepare($check_sql);
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            echo "<script>alert('Email already exists!'); window.location.href='admin_management.html';</script>";
        } else {
            $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
            $stmt = $pdo->prepare($insert_sql);
            
            $inserted = $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $hashed_password,
                'role'     => $role
            ]);

            if ($inserted) {
                echo "<script>alert('New Admin Added Successfully!'); window.location.href='admin_management.html';</script>";
            } else {
                echo "<script>alert('Failed to add admin.'); window.location.href='admin_management.html';</script>";
            }
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        echo "<script>alert('A system error occurred.'); window.location.href='admin_management.html';</script>";
    }
}
?>