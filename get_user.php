<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['full_name'])) {
    echo json_encode(['status' => 'success', 'name' => $_SESSION['full_name']]);
} elseif (isset($_SESSION['user_name'])) {
    echo json_encode(['status' => 'success', 'name' => $_SESSION['user_name']]);
} else {
    echo json_encode(['status' => 'error']);
}
?>