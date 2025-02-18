<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "crazekicks";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    die("User not logged in.");
}

// Get the item ID from the POST request
$itemId = isset($_POST['itemID']) ? intval($_POST['itemID']) : 0;

if ($itemId > 0) {
    // Remove the item from the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE itemID = ? AND email = ?");
    $stmt->bind_param("is", $itemId, $_SESSION['email']);

    if ($stmt->execute()) {
        echo "Item removed from cart successfully.";
    } else {
        echo "Error removing item from cart.";
    }

    $stmt->close();
} else {
    echo "Invalid item ID.";
}

$conn->close();
?>