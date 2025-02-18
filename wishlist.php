<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "crazekicks";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to view your wish list.'); window.location.href='login.php';</script>";
    exit();
}

// Fetch wishlist items
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT i.id, i.name, i.price, i.image 
                        FROM items i 
                        JOIN wish_list w ON i.id = w.itemID 
                        WHERE w.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wish List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>
    
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-6">Your Wish List</h1>
        
        <div class="grid grid-cols-1 gap-6">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Decode JSON image array
                    $images = json_decode($row['image'], true);
                    $firstImage = (is_array($images) && count($images) > 0) ? $images[0] : "uploads/default.jpg";
                    
                    echo "<div class='bg-white shadow-md rounded-lg p-4 flex justify-between items-center'>
                            <div>
                                <p class='text-lg font-semibold'>" . htmlspecialchars($row['name']) . "</p>
                                <p class='text-gray-600'>Price: $" . htmlspecialchars($row['price']) . "</p>
                                <button onclick='removeFromWishlist(" . $row['id'] . ")' 
                                        class='mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700'>
                                    Remove
                                </button>
                            </div>
                            <img src='" . htmlspecialchars($firstImage) . "' alt='Product Image' 
                                 class='w-24 h-24 object-cover rounded-lg'>
                          </div>";
                }
            } else {
                echo "<p class='text-center text-gray-600'>Your wish list is empty.</p>";
            }
            $stmt->close();
            ?>
        </div>
    </div>

    <script>
        function removeFromWishlist(itemId) {
            fetch('removewishlist.php', {
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
<!--Close database connection at the end-->
<?php
$conn->close(); 
?>
