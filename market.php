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

$isLoggedIn = isset($_SESSION['user_id']);

// Fetch items from the database
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market - Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <nav class="bg-white p-4 shadow-lg flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="logos/logo.png" alt="Craze Kicks Logo" class="max-h-16">
            <a href="index.php" class="text-xl font-semibold">Craze Kicks</a>
        </div>
        <div class="flex space-x-4 items-center">
            <?php if ($isLoggedIn): ?>
                <span class="text-gray-700">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a>
            <?php else: ?>
                <a href="login.php" class="text-gray-700 hover:text-blue-600">Login</a>
                <a href="register.php" class="text-gray-700 hover:text-blue-600">Create Account</a>
            <?php endif; ?>
            <a href="cart.html" class="relative text-gray-700 hover:text-blue-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 4m10.6 0H7.6M11 16h2m-5 4h6a3 3 0 003-3H5a3 3 0 003 3z"></path>
                </svg>
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-4 h-4 text-center">1</span>
            </a>
        </div>
    </nav>

    <h1 class="text-center text-2xl font-bold mt-4">Market</h1>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($item = $result->fetch_assoc()): ?>
                <?php
                $images = json_decode($item['image'], true);
                $firstImage = $images[0] ?? 'default.jpg'; // Default if no image found
                ?>
                <a href="product.php?id=<?php echo $item['id']; ?>" class="border p-4 rounded hover:shadow-lg">
                    <img src="<?php echo htmlspecialchars($firstImage); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-40 object-cover">
                    <h2 class="font-bold mt-2"><?php echo htmlspecialchars($item['name']); ?></h2>
                    <p class="text-green-600">$<?php echo number_format($item['price'], 2); ?></p>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No items available in the market.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>

