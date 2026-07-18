<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $selected_role = $_POST['role'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            
            if ($selected_role !== $user['role']) {
                echo "Access Denied! Your account role does not match the selected role.";
                exit();
            }

            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            
            if ($selected_role === 'customer') {
                header("Location: customer_home.html");
                exit();
            } elseif ($selected_role === 'admin') {
                header("Location: adminDashboard.html");
                exit();
            } elseif ($selected_role === 'owner') {
                header("Location: Owner_home.html");
                exit();
            } else {
                echo "Invalid role selected!";
            }
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with this email!";
    }
}
$conn->close();
?>