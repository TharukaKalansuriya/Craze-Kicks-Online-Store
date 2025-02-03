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

// Fetch user details
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $errorMessage = "User not found.";
    }
    $stmt->close();
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
        // Update the user in the database
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $role, $userId);

        if ($stmt->execute()) {
            $successMessage = "User updated successfully.";
        } else {
            $errorMessage = "Error updating user: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
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

    <footer class="bg-gray-800 text-white p-6 mt-8 text-center">
        <p>&copy; 2024 Craze Kicks. All rights reserved.</p>
    </footer>
</body>
</html>
