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

// Handle out of stock or delete item requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['out_of_stock'])) {
        $itemId = $_POST['item_id'];
        $stmt = $conn->prepare("UPDATE items SET status = 'out_of_stock' WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['delete_item'])) {
        $itemId = $_POST['item_id'];
        $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch market items
$itemsQuery = "SELECT * FROM items";
$itemsResult = $conn->query($itemsQuery);

// Fetch users
$usersQuery = "SELECT * FROM users";
$usersResult = $conn->query($usersQuery);

// Handle user role filtering
$filterRole = isset($_POST['filter_role']) ? $_POST['filter_role'] : '';
$filteredUsersQuery = "SELECT * FROM users";
if ($filterRole) {
    $filteredUsersQuery .= " WHERE role = '$filterRole'";
}
$filteredUsersResult = $conn->query($filteredUsersQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Craze Kicks</title>
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
        <h1 class="text-2xl font-bold mb-6">Welcome, <?php echo $_SESSION['name']; ?>!</h1>

        <div class="mb-6">
            <a href="additem.php" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Add Item</a>
            <a href="market.php" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">View Market</a>
        </div>

        <!-- Market Section -->
        <div id="market" class="mb-8">
            <h2 class="text-xl font-bold mb-4">Market Products</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Description</th>
                        <th class="border px-4 py-2">Price</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $itemsResult->fetch_assoc()): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $item['name']; ?></td>
                        <td class="border px-4 py-2"><?php echo $item['description']; ?></td>
                        <td class="border px-4 py-2"><?php echo $item['price']; ?></td>
                        <td class="border px-4 py-2"><?php echo $item['status'] == 'out_of_stock' ? 'Out of Stock' : 'Available'; ?></td>
                        <td class="border px-4 py-2">
                            <form method="POST" class="inline">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <?php if ($item['status'] !== 'out_of_stock'): ?>
                                    <button type="submit" name="out_of_stock" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Out of Stock</button>
                                <?php endif; ?>
                                <button type="submit" name="delete_item" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Users Section -->
        <div id="users">
            <h2 class="text-xl font-bold mb-4">All Users</h2>
            <form method="POST" class="mb-4">
                <label for="filter_role" class="font-semibold">Filter by Role:</label>
                <select name="filter_role" id="filter_role" class="border rounded px-2 py-1">
                    <option value="">All</option>
                    <option value="customer">Customer</option>
                    <option value="sales_keeper">Sales Keeper</option>
                    <option value="shop_manager">Shop Manager</option>
                    <option value="worker">Worker</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">Filter</button>
            </form>

            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Role</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $filteredUsersResult->fetch_assoc()): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $user['name']; ?></td>
                        <td class="border px-4 py-2"><?php echo $user['email']; ?></td>
                        <td class="border px-4 py-2"><?php echo $user['role']; ?></td>
                        <td class="border px-4 py-2">
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="bg-gray-800 text-white p-6 mt-8 text-center">
        <p>&copy; 2024 Craze Kicks. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
