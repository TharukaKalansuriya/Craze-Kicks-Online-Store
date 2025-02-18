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
    echo "<script>alert('User not logged in!'); window.history.back();</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemID = isset($_POST['itemID']) ? intval($_POST['itemID']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $email = $_SESSION['email'];

    if ($itemID > 0) {
        // Fetch the item details, including the image
        $stmt = $conn->prepare("SELECT id, image, price FROM items WHERE id = ?");
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        $stmt->close();

        if (!$item) {
            echo "<script>alert('Invalid product!'); window.location.href = 'product.php?id=$itemID';</script>";
            exit();
        }
        // Get image from items table
        $image = $item['image']; 
         // Get price from items table
        $price = $item['price'];

        // Insert the itemID, email, image, price, and quantity into cart
        $stmt = $conn->prepare("INSERT INTO cart (itemID, email, image, price, qty) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issdi", $itemID, $email, $image, $price, $quantity);

        if ($stmt->execute()) {
            echo "<script>alert('Added to cart!'); window.location.href = 'product.php?id=$itemID';</script>";
        } else {
            echo "<script>alert('Error adding to cart!'); window.location.href = 'product.php?id=$itemID';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Invalid product! itemID is: " . $itemID . "'); window.location.href = 'product.php';</script>";
    }
}

$conn->close();
?>