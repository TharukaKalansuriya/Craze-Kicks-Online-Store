<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); // Check if user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Sneaker Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // Update total price when quantity is changed
        function updateTotal() {
            const cartItems = document.querySelectorAll(".cart-item");
            let total = 0;
            cartItems.forEach(item => {
                const price = parseFloat(item.dataset.price);
                const quantity = parseInt(item.querySelector(".quantity").value);
                total += price * quantity;
            });
            document.getElementById("total-price").textContent = `Total: $${total.toFixed(2)}`;
        }
    </script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
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

    <!-- Shopping Cart Content -->
    <main class="p-8">
        <h1 class="text-3xl font-bold mb-4">Your Shopping Cart</h1>

        <!-- Cart Items -->
        <div id="cart-items">
            <!-- Example Cart Item (Dynamic) -->
            <div class="bg-white p-4 shadow rounded mb-4 cart-item" data-price="100">
                <div class="flex items-center">
                    <img src="sneaker1.jpg" alt="Sneaker" class="w-20 h-20 object-cover rounded">
                    <div class="ml-4 flex-1">
                        <h2 class="text-lg font-semibold">Product Name</h2>
                        <p class="text-gray-600">$100</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="quantity" class="text-gray-600">Qty:</label>
                        <input type="number" min="1" value="1" class="quantity border rounded w-16 p-1 text-center" oninput="updateTotal()">
                    </div>
                    <div class="ml-auto">
                        <button class="bg-red-500 text-white py-1 px-4 rounded">Remove</button>
                    </div>
                </div>
            </div>
            <!-- Additional items can be added similarly -->
        </div>

        <!-- Total Price -->
        <div class="text-right font-semibold text-lg mt-4" id="total-price">Total: $0.00</div>
        <button class="mt-4 bg-blue-600 text-white py-2 px-4 rounded">Checkout</button>
    </main>
</body>
</html>
