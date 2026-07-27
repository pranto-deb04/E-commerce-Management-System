<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $stock = isset($_POST['stock']) ? $_POST['stock'] : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    $imagePath = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

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
                alert('Error: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
} else {
    header("Location: addProducts.html");
    exit();
}

$conn->close();
?>