<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

$itemId = $data['id'];
$size = $data['size'];
$quantity = $data['quantity'];

// Store item in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = ['id' => $itemId, 'size' => $size, 'quantity' => $quantity];
echo json_encode(['status' => 'success']);
?>
