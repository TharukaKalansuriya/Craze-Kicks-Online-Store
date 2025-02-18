<?php
// Check if a session is already active before starting a new one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']); // Check if the user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <!-- Navigation Bar -->
    <nav class="bg-white p-4 shadow-lg flex justify-between items-center relative">
        <!-- Logo and Brand Name -->
        <div class="flex items-center space-x-4">
            <div class="absolute left-1/2 transform -translate-x-1/2">
                <img src="logos/logo.png" alt="Craze Kicks Logo" class="max-h-24">
            </div>
            <a href="index.php" class="text-xl font-semibold">Craze Kicks</a>
        </div>

        <!-- Hamburger Icon for Mobile -->
        <div class="md:hidden flex items-center space-x-4">
            <button id="hamburger" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Links for Larger Screens -->
        <div class="hidden md:flex space-x-4 items-center">
            <?php if ($isLoggedIn): ?>
                <!-- Display user's name if logged in -->
                <span class="text-gray-700">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a>
            <?php else: ?>
                <!-- Display login and register links if not logged in -->
                <a href="login.php" class="text-gray-700 hover:text-blue-600">Login</a>
                <a href="register.php" class="text-gray-700 hover:text-blue-600">Create Account</a>
            <?php endif; ?>
            <!-- Wishlist and Cart Links -->
            <a href="wishlist.php" class="text-gray-700 hover:text-blue-600">Wish List</a>
            <a href="cart.php" class="relative text-gray-700 hover:text-blue-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 4m10.6 0H7.6M11 16h2m-5 4h6a3 3 0 003-3H5a3 3 0z"></path>
                </svg>
                <!-- Cart Item Count -->
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-4 h-4 text-center">1</span>
            </a>
        </div>

        <!-- Mobile Menu (Hidden by Default) -->
        <div id="mobile-menu" class="hidden absolute top-16 left-0 w-full bg-white bg-opacity-80 shadow-lg flex-col items-start p-4 space-y-2">
            <?php if ($isLoggedIn): ?>
                <!-- Display user's name if logged in -->
                <span class="text-gray-700 block">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                <a href="logout.php" class="text-gray-700 block hover:text-blue-600">Logout</a>
            <?php else: ?>
                <!-- Display login and register links if not logged in -->
                <a href="login.php" class="text-gray-700 block hover:text-blue-600">Login</a>
                <a href="register.php" class="text-gray-700 block hover:text-blue-600">Create Account</a>
            <?php endif; ?>
            <!-- Wishlist and Cart Links -->
            <a href="wishlist.php" class="text-gray-700 block hover:text-blue-600">Wish List</a>
            <a href="cart.php" class="relative text-gray-700 block hover:text-blue-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 4m10.6 0H7.6M11 16h2m-5 4h6a3 3 0 003-3H5a3 3 0z"></path>
                </svg>
                <!-- Cart Item Count -->
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-4 h-4 text-center">1</span>
            </a>
        </div>
    </nav>

    <!-- JavaScript for Mobile Menu Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hamburger = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobile-menu');

            // Toggle mobile menu visibility
            hamburger.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>