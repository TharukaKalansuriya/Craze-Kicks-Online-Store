<!-- login.php -->
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "crazekicks";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            
            // Redirect based on role
            if ($user['role'] === 'customer') {
                header("Location: index.php");
            } elseif ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] === 'sales_keeper') {
                header("Location: sales_keeper_dashboard.php");
            } elseif ($user['role'] === 'shop_manager') {
                header("Location: shop_manager_dashboard.php");
            } elseif ($user['role'] === 'worker') {
                header("Location: worker_dashboard.php");
            }
            exit();
        } else {
            $errorMessage = "Incorrect password.";
        }
    } else {
        $errorMessage = "No user found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Log In to Craze Kicks</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="mb-4 text-red-600 text-center"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                <input type="email" id="email" name="email" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your email">
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input type="password" id="password" name="password" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your password">
            </div>
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none">
                Log In
            </button>
        </form>
        <div class="mt-6 text-center">
            <a href="#" class="text-blue-600 hover:underline">Forgot Password?</a>
        </div>
        <div class="mt-4 text-center">
            <p class="text-gray-700">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Create Account</a></p>
        </div>
    </div>
</body>
</html>
