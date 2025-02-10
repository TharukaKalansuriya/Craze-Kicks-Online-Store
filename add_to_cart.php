<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "crazekicks";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful, else return an error response
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Get data from the request (sent as JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$user_email = $_SESSION['user_email']; // Get the logged-in user's email
$item_id = intval($data['id']);        // Get item ID from request
$size = $data['size'];                 // Get size from request
$quantity = intval($data['quantity']); // Get quantity from request

// Fetch item details from the 'items' table based on the item ID
$sql = "SELECT name, price FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

// Check if the item exists
if (!$item) {
    echo json_encode(["success" => false, "message" => "Product not found"]);
    exit;
}

// Get the item name and price
$item_name = $item['name'];
$item_price = $item['price'];

// Insert the item into the cart table
$sql = "INSERT INTO cart (item, price, qty, user_email, size) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdisi", $item_name, $item_price, $quantity, $user_email, $size);

// Check if the item was successfully added to the cart
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Item added to cart"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add to cart"]);
}

$conn->close();
?>
