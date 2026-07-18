<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_name'])) {
    echo json_encode(['status' => 'success', 'name' => $_SESSION['user_name']]);
} else {
    echo json_encode(['status' => 'error']);
}
?>