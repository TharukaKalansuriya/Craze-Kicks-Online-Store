<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$servername = "localhost"; 
$username = "root";
$password = "1234";
$dbname = "crazekicks"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle out of stock, delete item, and update offer requests
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

    // Handle marking item as an offer
    if (isset($_POST['is_offer'])) {
        $itemId = $_POST['item_id'];
        $isOffer = isset($_POST['is_offer']) ? 1 : 0;
        $stmt = $conn->prepare("UPDATE items SET is_offer = ? WHERE id = ?");
        $stmt->bind_param("ii", $isOffer, $itemId);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch market items
$itemsQuery = "SELECT * FROM items";
$itemsStmt = $conn->prepare($itemsQuery);
$itemsStmt->execute();
$itemsResult = $itemsStmt->get_result();

// Fetch users
$usersQuery = "SELECT * FROM users";
$usersStmt = $conn->prepare($usersQuery);
$usersStmt->execute();
$usersResult = $usersStmt->get_result();

// Handle user role filtering
$filterRole = isset($_POST['filter_role']) ? $_POST['filter_role'] : '';
$filteredUsersQuery = "SELECT * FROM users";
if ($filterRole) {
    $filteredUsersQuery .= " WHERE role = ?";
    $filteredUsersStmt = $conn->prepare($filteredUsersQuery);
    $filteredUsersStmt->bind_param("s", $filterRole);
    $filteredUsersStmt->execute();
    $filteredUsersResult = $filteredUsersStmt->get_result();
} else {
    $filteredUsersStmt = $conn->prepare($filteredUsersQuery);
    $filteredUsersStmt->execute();
    $filteredUsersResult = $filteredUsersStmt->get_result();
}

// Include TCPDF library for report generation
require_once('tcpdf.php');

// Handle report generation
if (isset($_POST['generate_report'])) {
    // Create PDF document
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Title
    $pdf->Cell(0, 10, 'Craze Kicks Admin Report', 0, 1, 'C');
    $pdf->Ln(10);

    // Market Items Report
    $pdf->Cell(0, 10, 'Market Items Report', 0, 1, 'L');
    $pdf->Ln(4);

    // Fetch items and write to PDF
    $pdf->Cell(40, 10, 'Item Name', 1);
    $pdf->Cell(30, 10, 'Price', 1);
    $pdf->Cell(30, 10, 'Status', 1);
    $pdf->Cell(30, 10, 'Offer', 1);
    $pdf->Ln();

    while ($item = $itemsResult->fetch_assoc()) {
        $pdf->Cell(40, 10, $item['name'], 1);
        $pdf->Cell(30, 10, $item['price'], 1);
        $pdf->Cell(30, 10, $item['status'] == 'out_of_stock' ? 'Out of Stock' : 'Available', 1);
        $pdf->Cell(30, 10, $item['is_offer'] == 1 ? 'Yes' : 'No', 1);
        $pdf->Ln();
    }

    $pdf->Ln(10);

    // Users Report
    $pdf->Cell(0, 10, 'Users Report', 0, 1, 'L');
    $pdf->Ln(4);

    // Fetch users and write to PDF
    $pdf->Cell(40, 10, 'Name', 1);
    $pdf->Cell(60, 10, 'Email', 1);
    $pdf->Cell(40, 10, 'Role', 1);
    $pdf->Ln();

    while ($user = $filteredUsersResult->fetch_assoc()) {
        $pdf->Cell(40, 10, $user['name'], 1);
        $pdf->Cell(60, 10, $user['email'], 1);
        $pdf->Cell(40, 10, $user['role'], 1);
        $pdf->Ln();
    }

    // Output the PDF to the browser
    $pdf->Output('admin_report.pdf', 'D');
    exit();
}
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

        <!-- Generate Report Button -->
        <form method="POST" class="mb-6">
            <button type="submit" name="generate_report" class="bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">Generate PDF Report</button>
        </form>

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
                        <th class="border px-4 py-2">Offer</th>
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
                        <td class="border px-4 py-2 text-center">
                            <form method="POST">
                                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                <input type="checkbox" name="is_offer" value="1" <?php echo $item['is_offer'] == 1 ? 'checked' : ''; ?> onchange="this.form.submit();">
                            </form>
                        </td>
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
                        <th class="border px-4 py-2">Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $filteredUsersResult->fetch_assoc()): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo $user['name']; ?></td>
                        <td class="border px-4 py-2"><?php echo $user['email']; ?></td>
                        <td class="border px-4 py-2"><?php echo $user['role']; ?></td>
                        <td class="p-4 px-4 py-2">
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="text-blue-600 hover:underline">Edit</a>
                    </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
 
    <!-- Footer -->
    <?php include 'footer.php'; ?>
</html>

