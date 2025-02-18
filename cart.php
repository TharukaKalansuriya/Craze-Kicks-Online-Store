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

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to view your cart.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch cart items
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT i.id, i.name, i.price, i.image, c.qty 
                        FROM items i 
                        JOIN cart c ON i.id = c.itemID 
                        WHERE c.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

 // Initialize total price
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>
    
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-6">My Cart</h1>
        
        <div class="grid grid-cols-1 gap-6">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Decode JSON image array
                    $images = json_decode($row['image'], true);
                    $firstImage = (is_array($images) && count($images) > 0) ? $images[0] : "uploads/default.jpg";

                    // Calculate subtotal for each item
                    $subtotal = $row['price'] * $row['qty'];
                    $total += $subtotal; // Add to total price
                    
                    echo "<div class='bg-white shadow-md rounded-lg p-4 flex justify-between items-center'>
                            <div>
                                <p class='text-lg font-semibold'>" . htmlspecialchars($row['name']) . "</p>
                                <p class='text-gray-600'>Price: $" . htmlspecialchars($row['price']) . "</p>
                                <p class='text-gray-600'>Quantity: " . htmlspecialchars($row['qty']) . "</p>
                                <p class='text-gray-600'>Subtotal: $" . number_format($subtotal, 2) . "</p>
                                <button onclick='removeFromCart(" . $row['id'] . ")' 
                                        class='mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700'>
                                    Remove
                                </button>
                            </div>
                            <img src='" . htmlspecialchars($firstImage) . "' alt='Product Image' 
                                 class='w-24 h-24 object-cover rounded-lg'>
                          </div>";
                }
            } else {
                echo "<p class='text-center text-gray-600'>Your cart is empty.</p>";
            }
            $stmt->close();
            ?>
        </div>

        <!-- Display Total Price -->
        <div class="text-right font-semibold text-lg mt-6">
            Total: $<?php echo number_format($total, 2); ?>
        </div>
         <!-- Purchase Button -->
         <button onclick="window.location.href='payment.php'" class="bg-green-500 hover:bg-green-600 text-white px-10 py-2 rounded">
                        Check Out
                    </button>
    </div>

    <script>
        function removeFromCart(itemId) {
            fetch('removeitem.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'itemID=' + itemId
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            });
        }
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>

<!-- Close database connection at the end -->
<?php
$conn->close();
?>