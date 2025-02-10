<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Craze Kicks</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <!-- Ensure Bootstrap CSS and JS are Loaded -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    @keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
    }
    .browse-market {
    background-color: rgba(72, 187, 120, 0.7); 
    }

    .browse-market:hover {
    background-color: rgba(72, 187, 120, 1); 
    }

    .browse-market {
    animation: moveLeftRight 7s ease-in-out infinite;
    }
    @keyframes moveLeftRight {
20%, 100% { transform: translateX(0); }
    20% { transform: translateX(-150px); }
    100% { transform: translateX(-10px); }
100% {
    transform: translateX(0px); 
}
    }   
    a {
    text-decoration: none;
    }
    </style>
    <script>
        // Function to update total price based on selected items
        function updateTotal() {
            const cartItems = document.querySelectorAll(".cart-item input[type='checkbox']:checked");
            let total = 0;
            
            cartItems.forEach(item => {
                const price = parseFloat(item.closest('.cart-item').dataset.price);
                const quantity = parseInt(item.closest('.cart-item').querySelector(".quantity").value);
                total += price * quantity;
            });
            
            document.getElementById("total-price").textContent = `Total: $${total.toFixed(2)}`;
            document.getElementById("checkout-button").disabled = cartItems.length === 0;
        }

        // Redirect to checkout page
        function showCheckoutPopup() {
            window.location.href = 'checkoutfe.html';
        }

        document.addEventListener("DOMContentLoaded", function() {
            updateTotal();
            
            // Add event listeners to checkboxes
            document.querySelectorAll(".cart-item input[type='checkbox']").forEach(checkbox => {
                checkbox.addEventListener("change", updateTotal);
            });
            
            // Add event listeners to quantity inputs
            document.querySelectorAll(".quantity").forEach(input => {
                input.addEventListener("input", updateTotal);
            });
        });
        
        // Mobile menu toggle
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');
        
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</head>
<body class="bg-gray-100">
    
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>
    
    <!-- Main Content -->
    <main class="p-8">
        
        <!-- Central Hero Image -->
        <div class="main-hero w-full flex items-center justify-center h-96 mb-8 bg-cover bg-center" style="background-image: url('images/central-image.jpg');">
        <h1 class="text-white  text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold">Welcome to Craze Kicks</h1>

        </div>

        <!--Offers Section-->
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "crazekicks";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch offer items from the database
        $query = "SELECT * FROM items WHERE is_offer = 1";
        $result = mysqli_query($conn, $query);
        ?>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php 
        // Decode the JSON string to get an array of images
        $images = json_decode($row['image'], true);
        // Unique ID for each carousel
        $carouselId = "carousel_" . $row['id']; 
        ?>
        
        <div class="offer-item bg-white p-4 rounded-lg shadow-lg">
            <!-- Bootstrap Carousel -->
            <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= $image ?>" class="d-block w-100 h-64 object-cover" alt="Offer Image">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Carousel Navigation -->
                <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>

            <!-- Product Details -->
            <h3 class="font-semibold text-gray-800 mt-4"><?= $row['name']; ?></h3>
            <p class="text-red-600 font-semibold">$<?= $row['price']; ?></p>
            <a href="product.php?id=<?= $row['id']; ?>" 
            class="mt-4 block text-center bg-gradient-to-r from-green-300 to-yellow-300 text-black font-bold py-2 px-4 rounded-lg transition-all 
            duration-300 hover:from-yellow-300 hover:to-green-300">Shop Now</a>
        </div>
    <?php endwhile; ?>
</div>

    </main>
    
    <!-- Browse Market Button -->
    <div class="fixed bottom-8 w-full flex justify-end">
    <a href="market.php" 
       class="bg-green-600 bg-opacity-60 text-white px-6 py-3 rounded-full font-bold flex items-center space-x-2 hover:bg-opacity-100 transition ease-in-out duration-300 browse-market">
        <span>Browse Market</span>
        <svg
            class="w-6 h-6 ml-2 arrow-bounce"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 12h14m-7-7l7 7-7 7"
            ></path>
        </svg>
    </a>
    </div>

    
    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>     
</html>
