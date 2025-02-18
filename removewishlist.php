<?php
session_start();

// connection class
require_once 'connection.php'; 

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    die("User not logged in.");
}

// Get the item ID from the POST request
$itemId = isset($_POST['itemID']) ? intval($_POST['itemID']) : 0;

if ($itemId > 0) {
    // Create an instance of the Database class
    $db = new Database();

    // Prepare the SQL query
    $sql = "DELETE FROM wish_list WHERE itemID = ? AND email = ?";
    $params = [$itemId, $_SESSION['email']];

    // Execute the query
    $stmt = $db->executeQuery($sql, $params);

    if ($stmt->affected_rows > 0) {
        echo "Item removed from Wish List Successfully!";
    } else {
        echo "Error removing item from Wish List.";
    }
} else {
    echo "Invalid item ID.";
}
?>