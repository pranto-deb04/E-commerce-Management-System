<?php
require_once 'db.php';

$name = 'Pranto';
$email = 'owner@gmail.com';
$password = '12341234';
$role = 'owner';

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    $check_sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($check_sql);
    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) {
        $update_sql = "UPDATE users SET password = :password, role = :role WHERE email = :email";
        $stmt = $pdo->prepare($update_sql);
        $updated = $stmt->execute([
            'password' => $hashed_password,
            'role'     => $role,
            'email'    => $email
        ]);

        if ($updated) {
            echo "<h2 style='color:green;'>Success! Owner Password Updated.</h2>";
        } else {
            echo "<h2 style='color:red;'>Error updating record.</h2>";
        }
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
            echo "<h2 style='color:green;'>Success! Owner Created Successfully.</h2>";
        } else {
            echo "<h2 style='color:red;'>Error inserting record.</h2>";
        }
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<h2 style='color:red;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
?>