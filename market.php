<?php
session_start();

// Include the Database class
require_once 'connection.php';

// Initialize the Database class
$db = new Database();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Fetch items from the database using prepared statements
$query = "SELECT * FROM items";
$items = $db->fetchAll($query); // Fetch all items
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market - Craze Kicks</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    <!-- Main Content -->
    <h1 class="text-center text-2xl font-bold mt-4">Market</h1>

    <!-- Include the JavaScript file -->
    <script src="promotion.js" defer></script>

    <!-- Full-width promo banner before products -->
    <div class="w-full flex justify-center">
        <promo-banner></promo-banner>
    </div>
    <div>
    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-4">
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
                <?php
                // Decode the JSON string to get an array of images
                $images = json_decode($item['image'], true);
                // Default if no image found
                $firstImage = $images[0] ?? 'default.jpg';
                ?>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <!-- Carousel for product images -->
                    <div id="carousel<?php echo $item['id']; ?>" class="carousel slide h-48 w-full object-cover rounded-t-lg">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $image): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-full h-48 object-cover" alt="Product Image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Carousel Navigation -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $item['id']; ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $item['id']; ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <!-- Product Details -->
                    <div class="mt-4">
                        <h2 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($item['name']); ?></h2>
                        <p class="text-gray-600 mt-2">$<?php echo number_format($item['price'], 2); ?></p>
                        <!-- View Product Button with Tailwind Styles -->
                        <div class="items-center">
                            <button onclick="location.href='product.php?id=<?php echo $item['id']; ?>'" 
                                    class="mt-4 block text-center bg-gradient-to-r from-green-300 to-yellow-300 text-black font-bold py-2 px-4 rounded-lg transition-all duration-300 hover:from-yellow-300 hover:to-green-300">
                                View Product
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-600">No items available in the market.</p>
        <?php endif; ?>
        
    </div>
    </div>
    
    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>