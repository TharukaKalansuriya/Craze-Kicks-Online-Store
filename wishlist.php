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
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $query = "SELECT i.id, i.name, i.price FROM items i 
                          JOIN wish_list w ON i.id = w.itemID WHERE w.email = '$email'";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='bg-white shadow-md rounded-lg p-4 '>
                                <p class='text-lg font-semibold'>" . $row['name'] . "</p>

                                <!--remove button-->
                                
                                <p class='text-gray-600'>Price: $" . $row['price'] . "</p>
                                <button class=' mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700'>
                                    Remove
                                </button>
                              </div>";
                    }
                } else {
                    echo "<p class='text-center text-gray-600'>Your wish list is empty.</p>";
                }
            } else {
                echo "<p class='text-center text-red-500'>Please log in to view your wish list.</p>";
            }
            ?>
        </div>
    </div>
    
    <script>
        function removeFromWishlist(itemId) {
            fetch('remove_wishlist.php', {
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
