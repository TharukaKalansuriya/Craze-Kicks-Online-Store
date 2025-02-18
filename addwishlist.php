<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "crazekicks";

// Create connection
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
    $email = $_SESSION['email'];

    if ($itemID > 0) {
        // Fetch the item details, including the image
        $stmt = $conn->prepare("SELECT id, image FROM items WHERE id = ?");
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

        // Insert the itemID, email, and image into wish_list
        $stmt = $conn->prepare("INSERT INTO wish_list (itemID, email, image) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $itemID, $email, $image);

        if ($stmt->execute()) {
            echo "<script>alert('Added to wishlist!'); window.location.href = 'product.php?id=$itemID';</script>";
        } else {
            echo "<script>alert('Error adding to wishlist!'); window.location.href = 'product.php?id=$itemID';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Invalid product! itemID is: " . $itemID . "'); window.location.href = 'product.php';</script>";
    }
}

$conn->close();
?>
