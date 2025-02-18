<?php
// Start the session if it hasn't already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is not logged in or is not a sales keeper
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales_keeper') {
    header("Location: login.php");
    exit;
}

// Include the Database class
require_once 'connection.php';

// Initialize the Database class
$db = new Database();

// Initialize messages
$errorMessage = '';
$successMessage = '';

// Handle adding a new item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $productName = htmlspecialchars(trim($_POST['product_name']));
    $productDescription = htmlspecialchars(trim($_POST['product_description']));
    $price = floatval($_POST['price']);
    $stock = isset($_POST['stock']) ? 1 : 0;

    // Validate inputs
    if (empty($productName) || empty($productDescription) || empty($price)) {
        $errorMessage = "All fields are required.";
    } else {
        // Use prepared statements to prevent SQL injection
        $query = "INSERT INTO items (name, description, price, stock) VALUES (?, ?, ?, ?)";
        $params = [$productName, $productDescription, $price, $stock];

        // Execute the query
        if ($db->executeQuery($query, $params)) {
            $successMessage = "Item added successfully.";
        } else {
            $errorMessage = "Error adding item.";
        }
    }
}

// Handle removing an item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $itemId = intval($_POST['item_id']);

    // Use prepared statements to prevent SQL injection
    $query = "DELETE FROM items WHERE id = ?";
    $params = [$itemId];

    // Execute the query
    if ($db->executeQuery($query, $params)) {
        $successMessage = "Item removed successfully.";
    } else {
        $errorMessage = "Error removing item.";
    }
}

// Handle updating stock status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_stock'])) {
    $itemId = intval($_POST['item_id']);
    $newStockStatus = intval($_POST['stock_status']);

    // Use prepared statements to prevent SQL injection
    $query = "UPDATE items SET stock = ? WHERE id = ?";
    $params = [$newStockStatus, $itemId];

    // Execute the query
    if ($db->executeQuery($query, $params)) {
        $successMessage = "Stock status updated successfully.";
    } else {
        $errorMessage = "Error updating stock.";
    }
}

// Fetch items for display
$query = "SELECT * FROM items";
$items = $db->fetchAll($query);

    // Handle updating stock status
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_stock'])) {
    $itemId = intval($_POST['item_id']); // Get the item ID
    $newStockStatus = intval($_POST['stock_status']); // Get the new stock status (0 or 1)

    // Use prepared statements to prevent SQL injection
    $query = "UPDATE items SET stock = ? WHERE id = ?";
    $params = [$newStockStatus, $itemId];

    // Execute the query
    if ($db->executeQuery($query, $params)) {
        $successMessage = "Stock status updated successfully.";
    } else {
        $errorMessage = "Error updating stock.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Keeper Dashboard - Add/Remove Items</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-white p-4 shadow-lg flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="index.php" class="text-xl font-semibold">Craze Kicks</a>
            <span class="text-gray-700">Sales Keeper Dashboard</span>
        </div>
        <div>
            <a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="p-8">
        <h1 class="text-2xl font-bold mb-6">Manage Items</h1>

        <!-- Display messages -->
        <?php if (!empty($errorMessage)): ?>
            <div class="mb-4 text-red-600"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="mb-4 text-green-600"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- Add Item Form -->
        <h2 class="text-xl font-semibold mb-4">Add New Item</h2>
        <form action="additem.php" method="POST" class="space-y-4">
            <input type="hidden" name="add_item" value="1">
            <div>
                <label for="product_name" class="block text-gray-700 font-semibold">Product Name</label>
                <input type="text" id="product_name" name="product_name" required 
                       class="w-full px-4 py-2 border rounded-lg focus:border-blue-500">
            </div>
            <div>
                <label for="product_description" class="block text-gray-700 font-semibold">Product Description</label>
                <textarea id="product_description" name="product_description" required 
                          class="w-full px-4 py-2 border rounded-lg focus:border-blue-500"></textarea>
            </div>
            <div>
                <label for="price" class="block text-gray-700 font-semibold">Price</label>
                <input type="number" id="price" name="price" required step="0.01" 
                       class="w-full px-4 py-2 border rounded-lg focus:border-blue-500">
            </div>
            <div>
                <input type="checkbox" id="stock" name="stock" value="1">
                <label for="stock" class="text-gray-700">In Stock</label>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Add Item</button>
        </form>

        <!-- Manage Items Section -->
        <h2 class="text-xl font-semibold mt-8 mb-4">Current Items</h2>
        <div class="overflow-x-auto bg-white p-4 shadow-lg rounded-lg">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-2 border">ID</th>
                        <th class="p-2 border">Name</th>
                        <th class="p-2 border">Price</th>
                        <th class="p-2 border">Stock</th>
                        <th class="p-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td class="p-2 border"><?php echo $item['id']; ?></td>
                            <td class="p-2 border"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td class="p-2 border"><?php echo $item['price']; ?></td>
                            <td class="p-2 border"><?php echo $item['stock'] ? "In Stock" : "Out of Stock"; ?></td>
                            <td class="p-2 border flex space-x-2">
                                <!-- Remove Item -->
                                <form method="POST" class="inline">
                                    <input type="hidden" name="remove_item" value="1">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="delete_item" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Remove</button>
                                </form>
                                <!-- Toggle Stock Status -->
                                <form method="POST" >
                                <input type="hidden" name="update_stock" value="1">
                                 <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                 <button type="submit" name="stock_status" value="<?php echo $item['stock'] ? 0 : 1; ?>" 
                                 class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                    Toggle Stock
                                  </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>