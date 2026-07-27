<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['productName']) ? trim($_POST['productName']) : '';
    $price = isset($_POST['productPrice']) ? floatval($_POST['productPrice']) : 0.0;
    $stock = isset($_POST['productQuantity']) ? intval($_POST['productQuantity']) : 0;
    $description = isset($_POST['productDescription']) ? trim($_POST['productDescription']) : '';

    $imagePath = "";

    if (isset($_POST['productImageUrl']) && !empty(trim($_POST['productImageUrl']))) {
        $imagePath = trim($_POST['productImageUrl']);
    }

    if (isset($_FILES['productImageFile']) && $_FILES['productImageFile']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES["productImageFile"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["productImageFile"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

    if (!empty($name) && $price > 0) {
        $sql = "INSERT INTO products (name, price, stock, description, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("sdiss", $name, $price, $stock, $description, $imagePath);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Product added successfully!');
                    window.location.href = 'adminDashboard.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: " . addslashes($stmt->error) . "');
                    window.history.back();
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Please enter valid product details.');
                window.history.back();
              </script>";
    }
} else {
    header("Location: addProducts.html");
    exit();
}

$conn->close();
?>