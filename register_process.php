<?php
ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>
                alert('Error: All fields are required!');
                window.history.back();
              </script>";
        exit;
    }

    try {
        $checkEmail = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($checkEmail);
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            echo "<script>
                    alert('Error: Email already exists!');
                    window.history.back();
                  </script>";
            exit;
        }

        $encrypted_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $insert_stmt = $pdo->prepare($sql);
        $success = $insert_stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $encrypted_password
        ]);

        if ($success) {
            echo "<script>
                    alert('Registration successful!');
                    window.location.href = 'login.html';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Error: Could not complete registration.');
                    window.history.back();
                  </script>";
            exit;
        }

    } catch (PDOException $e) {
        error_log("Registration Error: " . $e->getMessage());
        echo "<script>
                alert('Database Error: Unable to process request.');
                window.history.back();
              </script>";
        exit;
    }
}
?>