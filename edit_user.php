<?php
session_start();

// Redirect if user is not logged in or is not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Include the connection 
require_once 'connection.php';

// Initialize theDatabse class
$db = new Database();

// Initialize variables
$errorMessage = '';
$successMessage = '';
$user = [];

// Fetch user details
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Use prepared statement to fetch user details
    $query = "SELECT * FROM users WHERE id = ?";
    $user = $db->fetchOne($query, [$userId]);

    if (!$user) {
        $errorMessage = "User not found.";
    }
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Validate inputs
    if (empty($name) || empty($email)) {
        $errorMessage = "Name and email are required.";
    } else {
        // Use prepared statement to update user details
        $query = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
        $params = [$name, $email, $role, $userId];

        // Execute the update query
        if ($db->executeQuery($query, $params)) {
            $successMessage = "User updated successfully.";
        } else {
            $errorMessage = "Error updating user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-white p-4 shadow-lg flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="index.php" class="text-xl font-semibold">Craze Kicks</a>
            <a href="admin_dashboard.php" class="text-gray-700 hover:text-blue-600">Admin Dashboard</a>
        </div>
        <div>
            <a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="p-8">
        <h1 class="text-2xl font-bold mb-6">Edit User</h1>

        <!-- Display error message -->
        <?php if (!empty($errorMessage)): ?>
            <div class="mb-4 text-red-600"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Display success message -->
        <?php if (!empty($successMessage)): ?>
            <div class="mb-4 text-green-600"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- User Edit Form -->
        <form action="edit_user.php?id=<?php echo $userId; ?>" method="POST" class="space-y-6">
            <div>
                <label for="name" class="block text-gray-700 font-semibold">Name</label>
                <input type="text" id="name" name="name" required value="<?php echo $user['name']; ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter user's name">
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                <input type="email" id="email" name="email" required value="<?php echo $user['email']; ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter user's email">
            </div>

            <div>
                <label for="role" class="block text-gray-700 font-semibold">Role</label>
                <select id="role" name="role" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="customer" <?php echo ($user['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                    <option value="sales_keeper" <?php echo ($user['role'] == 'sales_keeper') ? 'selected' : ''; ?>>Sales Keeper</option>
                    <option value="shop_manager" <?php echo ($user['role'] == 'shop_manager') ? 'selected' : ''; ?>>Shop Manager</option>
                    <option value="worker" <?php echo ($user['role'] == 'worker') ? 'selected' : ''; ?>>Worker</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Update User</button>
        </form>
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>