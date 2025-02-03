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

$itemId = $_GET['id'];
$sql = "SELECT * FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

$images = json_decode($item['image'], true);
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['name']); ?> - Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex: 1;
        }
    </style>
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
    <div class="container mx-auto p-4 content">
        <div class="flex space-x-4">
            <div class="w-1/2">
                <!-- Bootstrap Carousel -->
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-100 h-96 object-cover" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="flex flex-col space-y-4 w-1/2">
                <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($item['name']); ?></h1>
                <p><?php echo htmlspecialchars($item['description']); ?></p>
                <p class="text-green-600 text-xl">$<?php echo number_format($item['price'], 2); ?></p>
                <label for="size" class="font-semibold">Select Size:</label>
                <select id="size" name="size" class="border rounded p-2">
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                    <option>11</option>
                </select>
                <label for="quantity" class="font-semibold">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" class="border rounded p-2 w-20">
                <div class="flex space-x-4">
                    <button onclick="addToCart(<?php echo $item['id']; ?>)" class="bg-blue-500 text-white px-4 py-2 rounded">Add to Cart</button>
                    <button class="bg-green-500 text-white px-4 py-2 rounded">Purchase</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addToCart(itemId) {
            const size = document.getElementById("size").value;
            const quantity = document.getElementById("quantity").value;

            fetch("add_to_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: itemId, size: size, quantity: quantity })
            }).then(response => {
                if (response.ok) alert("Added to cart!");
            });
        }
    </script>

    <footer class="bg-gray-800 text-white p-6 mt-8">
        <div class="container mx-auto text-center">
            <div class="mb-4">
                <a href="terms.html" class="text-blue-400 hover:underline">Terms and Conditions</a> |
                <a href="#" class="text-blue-400 hover:underline">Supporting Brands</a> |
                <a href="#" class="text-blue-400 hover:underline">Our Shop Location</a>
            </div>
            <p>&copy; 2024 Craze Kicks. All rights reserved.</p>
        </div>
    </footer>
</body>

<?php
$conn->close();
?>
