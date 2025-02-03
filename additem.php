<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = "1234"; // Your database password
$dbname = "crazekicks"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$errorMessage = '';
$successMessage = '';

// Add new item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $price = $_POST['price'];
    $stock = isset($_POST['stock']) ? 1 : 0; // 1 for in stock, 0 for out of stock

    // Validate inputs
    if (empty($productName) || empty($productDescription) || empty($price)) {
        $errorMessage = "All fields are required.";
    } else {
        // Handle image uploads
        $targetDir = "uploads/"; // Directory to save uploaded images
        $uploadOk = 1;
        $imagePaths = [];

        for ($i = 0; $i < 4; $i++) {
            if (isset($_FILES["image$i"]) && $_FILES["image$i"]["error"] == UPLOAD_ERR_OK) {
                $fileName = basename($_FILES["image$i"]["name"]);
                $targetFilePath = $targetDir . uniqid() . "_" . $fileName; // Unique file name

                // Check if the file is an image
                $check = getimagesize($_FILES["image$i"]["tmp_name"]);
                if ($check === false) {
                    $errorMessage = "File $fileName is not an image.";
                    $uploadOk = 0;
                    break;
                }

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["image$i"]["tmp_name"], $targetFilePath)) {
                    $imagePaths[] = $targetFilePath; // Store the file path
                } else {
                    $errorMessage = "Sorry, there was an error uploading your file: $fileName";
                    $uploadOk = 0;
                    break;
                }
            }
        }

        // If no image uploaded, add a placeholder
        if (empty($imagePaths)) {
            $imagePaths[] = "uploads/default.jpg";
        }

        // Convert image paths array to JSON for storage
        $imagePathsJson = json_encode($imagePaths);

        // Insert the new product into the database
        if ($uploadOk) {
            $stmt = $conn->prepare("INSERT INTO items (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $productName, $productDescription, $price, $stock, $imagePathsJson);

            if ($stmt->execute()) {
                $successMessage = "Item added successfully.";
            } else {
                $errorMessage = "Error adding item: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-white p-4 shadow-lg flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="index.php" class="text-xl font-semibold">Craze Kicks</a>
            <a href="admin_dashboard.php" class="text-gray-700 hover:text-blue-600">Admin Dashboard</a>
        </div>
        <div>
            <a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a>
        </div>
    </nav>

    <main class="p-8">
        <h1 class="text-2xl font-bold mb-6">Add New Item</h1>

        <!-- Display error message -->
        <?php if (!empty($errorMessage)): ?>
            <div class="mb-4 text-red-600"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Display success message -->
        <?php if (!empty($successMessage)): ?>
            <div class="mb-4 text-green-600"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- Add Item Form -->
        <form action="additem.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="product_name" class="block text-gray-700 font-semibold">Product Name</label>
                <input type="text" id="product_name" name="product_name" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter product name">
            </div>

            <div>
                <label for="product_description" class="block text-gray-700 font-semibold">Product Description</label>
                <textarea id="product_description" name="product_description" required 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                          placeholder="Enter product description"></textarea>
            </div>

            <div>
                <label for="price" class="block text-gray-700 font-semibold">Price</label>
                <input type="number" id="price" name="price" required step="0.01" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter product price">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Stock</label>
                <input type="checkbox" id="stock" name="stock" value="1" class="mr-2"> 
                <label for="stock" class="text-gray-700">In Stock</label>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Upload Images (Max 4)</label>
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <input type="file" name="image<?php echo $i; ?>" accept="image/*" class="mb-2">
                <?php endfor; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Add Item</button>
        </form>
    </main>

    <footer class="bg-gray-800 text-white p-6 mt-8">
        <div class="container mx-auto text-center">
            <div class="mb-4">
                <a href="termsfe.html" class="text-yellow-400 hover:underline">Terms and Conditions</a> |
                <a href="brandsfe.html" class="text-yellow-400 hover:underline">Supporting Brands</a> |
                <a href="https://maps.app.goo.gl/7RpMEkxRyQpapiJq6" class="text-yellow-400 hover:underline">Our Shop Location</a>
            </div>
            <p>&copy; 2024 Craze Kicks. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
