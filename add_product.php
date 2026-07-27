<?php
require_once 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = trim($_POST['productName']);
    $price = floatval($_POST['productPrice']);
    $description = isset($_POST['productDescription']) ? trim($_POST['productDescription']) : '';
    $image = isset($_POST['productImage']) ? trim($_POST['productImage']) : '';

    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $description, $price, $image);

    if ($stmt->execute()) {
        echo "<script>
                alert('Product added successfully!');
                window.location.href = 'customer_home.html#shop';
              </script>";
    } else {
        echo "<script>
                alert('Error: Could not add product.');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>