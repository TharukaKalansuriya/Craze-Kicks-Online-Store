<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Supporting Brands</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
        .brand-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
        }
        </style>
    </head>
    <body class="bg-white">
       <!--nav bar-->
       <?php include 'navbar.php'; ?>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden absolute top-20 left-0 w-full bg-white bg-opacity-95 flex-col items-center space-y-6 hidden">
            <a href="loginfe.html" class="text-gray-800 hover:text-green-600">Login</a>
            <a href="registerfe.html" class="text-gray-800 hover:text-green-600">Create Account</a>
            <a href="wishfe.html" class="text-gray-800 hover:text-green-600">Wish List</a>
            <a href="cartfe.html" class="relative text-gray-800 hover:text-green-600">
                <svg
                    class="h-6 w-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.6 4m10.6 0H7.6M11 16h2m-5 4h6a3 3 0 003-3H5a3 3 0 003 3z"
                    ></path>
                </svg>
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-4 h-4 text-center">3</span>
            </a>
        </div>
        <header class="text-center py-0 bg-gradient-to-r from-yellow-300 to-green-300">
            <h2 class="text-3xl font-bold text-gray-800">Supporting Brands</h2>
            <p class="text-lg text-gray-600 mt-2">Discover the brands that power our sneaker collection</p>
        </header>
        <main class="container mx-auto py-12 px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                <!-- Brand Cards -->
                <div class="bg-[#86efac] p-4 rounded-lg shadow-lg brand-hover">
                    <img src="logos/brand1.png" alt="Brand 1" class="w-full h-48 object-cover rounded">
                    <h2 class="text-xl font-semibold text-gray-800 mt-4">NIKE</h2>
                    <p class="text-gray-600 mt-2">Just Do It</p>
                </div>
                <div class="bg-[#fde047] p-4 rounded-lg shadow-lg brand-hover">
                    <img src="logos/brand2.png" alt="Brand 2" class="w-full h-48 object-cover rounded">
                    <h2 class="text-xl font-semibold text-gray-800 mt-4">new balance</h2>
                    <p class="text-gray-600 mt-2">Run Your Way</p>
                </div>
                <div class="bg-[#86efac] p-4 rounded-lg shadow-lg brand-hover">
                    <img src="logos/brand3.png" alt="Brand 3" class="w-full h-48 object-cover rounded">
                    <h2 class="text-xl font-semibold text-gray-800 mt-4">adiddas</h2>
                    <p class="text-gray-600 mt-2">Impossible is Nothing</p>
                </div>
                <div class="bg-[#fde047] p-4 rounded-lg shadow-lg brand-hover">
                    <img src="logos/brand4.png" alt="Brand 4" class="w-full h-48 object-cover rounded">
                    <h2 class="text-xl font-semibold text-gray-800 mt-4">FILA</h2>
                    <p class="text-gray-600 mt-2">Play it your way</p>
                </div>
            </div>
        </main>
             <!--footer-->
       <?php include 'footer.php'; ?>    
    </body>
</html>
