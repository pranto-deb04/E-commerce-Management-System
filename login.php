<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password.'); window.history.back();</script>";
        exit();
    }

    try {
        $sql  = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $user_role = strtolower(trim($user['role'] ?? 'customer'));

            $_SESSION['user_id']   = $user['id'];
            $_SESSION['id']        = $user['id']; // Backup key for other files
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role']      = $user_role;

            ob_clean();

            if ($user_role === 'owner') {
                header("Location: owner_home.php");
                echo "<script>window.location.href = 'owner_home.php';</script>";
            } elseif ($user_role === 'admin') {
                header("Location: adminDashboard.html");
                echo "<script>window.location.href = 'adminDashboard.html';</script>";
            } else {
                header("Location: customer_home.html");
                echo "<script>window.location.href = 'customer_home.html';</script>";
            }
            exit();

        } else {
            echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
            exit();
        }

    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        echo "<script>alert('A system error occurred. Please try again later.'); window.history.back();</script>";
        exit();
    }
}
?>