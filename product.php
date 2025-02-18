<?php
// Start the session if it hasn't already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the Database class
require_once 'connection.php';

// Initialize the Database class
$db = new Database();

// Generate a CSRF token if not already set
if (!isset($_SESSION['token1'])) {
    $_SESSION['token1'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// Get product ID safely
$itemId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch item details using prepared statements
$query = "SELECT * FROM items WHERE id = ?";
$item = $db->fetchOne($query, [$itemId]);

if (!$item) {
    die("<h1 class='text-center text-red-500 mt-10'>Product not found</h1>");
}

// Decode images safely
$images = json_decode($item['image'], true);
if (!is_array($images)) {
    $images = []; // Fallback to empty array
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['name']); ?> - Craze Kicks</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto p-4 content">
        <div class="flex flex-col md:flex-row space-x-0 md:space-x-6">
            <!-- Product Images (Carousel) -->
            <div class="md:w-1/2">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-100 h-96 object-cover rounded-lg shadow-lg" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Carousel Controls -->
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

            <!-- Product Details -->
            <div class="flex flex-col space-y-4 md:w-1/2">
                <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($item['name']); ?></h1>
                <p class="text-gray-700"><?php echo htmlspecialchars($item['description']); ?></p>
                <p class="text-green-600 text-xl font-semibold">$<?php echo isset($item['price']) ? number_format($item['price'], 2) : "N/A"; ?></p>

                <!-- Size Selection -->
                <div>
                    <label for="size" class="font-semibold block">Select Size:</label>
                    <select id="size" name="size" class="border rounded p-2 w-full">
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                    </select>
                </div>

                <!-- Quantity Selection -->
                <div>
                    <label for="quantity" class="font-semibold block">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="border rounded p-2 w-20">
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4">
                    <!-- Add to Wishlist Form -->
                    <form action="addwishlist.php" method="POST">
                        <input type="hidden" name="token1" value="<?php echo $_SESSION['token1']; ?>">
                        <input type="hidden" name="itemID" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Add to Wish List
                        </button>
                    </form>

                    <!-- Add to Cart Form -->
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <input type="hidden" name="itemID" value="<?php echo $item['id']; ?>">
                        <input type="hidden" name="quantity" id="quantityInput" value="1">
                        <button type="submit" class="bg-green-500 hover:bg-green-300 text-white px-4 py-2 rounded">
                            Add to Cart
                        </button>
                    </form>

                    <!-- Purchase Button -->
                    <button onclick="window.location.href='payment.php'" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        Purchase
                    </button>
                    <!-- Purchase Button -->
                    <button onclick="window.location.href='market.php'" class="bg-red-400 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Back
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to Update Quantity -->
    <script>
        document.getElementById('quantity').addEventListener('input', function() {
            document.getElementById('quantityInput').value = this.value;
        });
    </script>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>