<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        
        <!-- Logo Placeholder -->
        <div class="flex justify-center mb-6">
            <img src="logos/logo.png" alt="Logo" class="h-24"> 
        </div>
        
        <h2 class="text-2xl font-bold text-center mb-4">Secure Payment</h2>
        
        <!-- Card Payment Form -->
        <form>
            <div class="mb-4">
                <label class="block text-gray-700">Cardholder Name</label>
                <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="John Doe">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700">Card Number</label>
                <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="1234 5678 9012 3456">
            </div>
            
            <div class="flex gap-4 mb-4">
                <div class="w-1/2">
                    <label class="block text-gray-700">Expiration Date</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="MM/YY">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700">CVV</label>
                    <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="123">
                </div>
            </div>
            
            <!-- Payment Button -->
            <a href="market.php" class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold hover:bg-blue-700 text-center block">
  Pay Now
</a>

        </form>
        
        <!-- Payment Options Placeholder -->
        <div class="mt-6 text-center">
            <p class="text-gray-600 mb-2">Or pay with</p>
            <div class="flex justify-center gap-4">
                <img src="logos/pay1.png" alt="PayPal" class="h-10"> 
                <img src="logos/pay2.png" alt="Visa" class="h-10"> 
                <img src="logos/pay4.png" alt="MasterCard" class="h-10"> 
            </div>
        </div>
    </div>
</body>
</html>