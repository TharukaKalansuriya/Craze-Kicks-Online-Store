<?php
session_start();
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
    $email = $_SESSION['email'];

    if ($itemID > 0) {
        $stmt = $conn->prepare("SELECT id FROM items WHERE id = ?");
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "<script>alert('Invalid product!'); window.location.href = 'product.php?id=$itemID';</script>";
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO wish_list (itemID, email) VALUES (?, ?)");
        $stmt->bind_param("is", $itemID, $email);

        if ($stmt->execute()) {
            echo "<script>alert('Added to wishlist!'); window.location.href = 'product.php?id=$itemID';</script>";
        } else {
            echo "<script>alert('Error adding to wishlist!'); window.location.href = 'product.php?id=$itemID';</script>";
        }
    } else {
        echo "<script>alert('Invalid product! itemID is: " . $itemID . "'); window.location.href = 'product.php';</script>";
    }
}

$conn->close();
?>
