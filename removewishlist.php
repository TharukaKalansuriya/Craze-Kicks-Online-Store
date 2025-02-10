<?php
// Include database connection
include('db_connection.php');

// Check if product_id is set in the request
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $email = $_SESSION['email']; // Assuming the email is stored in the session after login

    // Prepare the SQL statement to delete the entry from the wish_list table
    $sql = "DELETE FROM wish_list WHERE itemID = ? AND email = ?";
    
    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("is", $product_id, $email); // Bind product ID and email
        if ($stmt->execute()) {
            echo "Product removed from wish list.";
        } else {
            echo "Error removing product from wish list.";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }
}
?>
