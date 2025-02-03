<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // Check if a user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gray-100">
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
            <a href="cart.php" class="relative text-gray-700 hover:text-blue-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 4m10.6 0H7.6M11 16h2m-5 4h6a3 3 0 003-3H5a3 3 0 003 3z"></path>
                </svg>
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-4 h-4 text-center">1</span>
            </a>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="p-8">
        <!-- Central Image -->
        <div class="main-hero w-full h-96 mb-8 bg-cover bg-center" style="background-image: url('images/central-image.jpg');">
            <h1 class="text-white text-4xl font-bold">Welcome to Craze Kicks</h1>
        </div>

        <!-- Special Offers Section -->
        <h2 class="text-2xl font-bold text-center mb-4">Special Offers</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <!-- Offer 1 -->
            <div class="offer-item">
                <img src="images/offer1.jpg" alt="Special Offer 1" class="w-full h-40 object-cover mb-4 rounded">
                <h3 class="font-semibold">Special Offer 1</h3>
                <p class="text-gray-600">$80</p>
                <button class="button-primary mt-4">Shop Now</button>
            </div>
            <!-- Offer 2 -->
            <div class="offer-item">
                <img src="images/offer2.jpg" alt="Special Offer 2" class="w-full h-40 object-cover mb-4 rounded">
                <h3 class="font-semibold">Special Offer 2</h3>
                <p class="text-gray-600">$90</p>
                <button class="button-primary mt-4">Shop Now</button>
            </div>
            <!-- Offer 3 -->
            <div class="offer-item">
                <img src="images/offer3.jpg" alt="Special Offer 3" class="w-full h-40 object-cover mb-4 rounded">
                <h3 class="font-semibold">Special Offer 3</h3>
                <p class="text-gray-600">$75</p>
                <button class="button-primary mt-4">Shop Now</button>
            </div>
        </div>
    </main>
    <!-- Browse Market Button -->
    <div class="fixed bottom-8 w-full flex justify-center">
        <a href="market.php" 
           class="bg-blue-600 text-white px-6 py-3 rounded-full font-bold flex items-center space-x-2 hover:bg-blue-700 transition ease-in-out duration-300">
            <span>Browse Market</span>
            <!-- Animated Arrow -->
            <svg class="w-6 h-6 ml-2 arrow-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <!-- Footer -->
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
</html>
