<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $encrypted_password = password_hash($password, PASSWORD_BCRYPT);

    $checkEmail = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('Error: Email already exists!');
                window.history.back();
              </script>";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($sql);
        $insert_stmt->bind_param("sss", $name, $email, $encrypted_password);

        if ($insert_stmt->execute()) {
            echo "<script>
                    alert('Registration successful!');
                    window.location.href = 'login.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: Could not complete registration.');
                    window.history.back();
                  </script>";
        }
        $insert_stmt->close();
    }
    
    $stmt->close();
}

$conn->close();
?>