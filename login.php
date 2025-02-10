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
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, name, role, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Store user details in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $email;

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'sales_keeper':
                    header("Location: sales_keeper.php");
                    break;
                case 'shop_manager':
                    header("Location: shop_manager_dashboard.php");
                    break;
                case 'worker':
                    header("Location: worker_dashboard.php");
                    break;
                default:
                    header("Location: index.php"); 
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
    <style>
        body {
            background: url('images/felog.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100 bg-opacity-90">
    <div class="w-full max-w-md bg-white bg-opacity-90 shadow-xl rounded-lg p-6">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">
            Welcome to <span class="text-green-600">Craze</span> <span class="text-yellow-500">Kicks</span>
        </h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="mb-4 text-red-600 text-center font-semibold">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                       placeholder="Enter your email">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                       placeholder="Enter your password">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-yellow-300 to-green-300 text-gray-700 font-bold py-2 rounded-xl shadow-lg hover:from-green-300 hover:to-yellow-200 focus:outline-none focus:ring-2 focus:ring-green-950 focus:ring-offset-2">
                Log In
            </button>
        </form>
        <div class="mt-4 text-center text-sm">
            <a href="forgetfe.html" class="text-green-600 hover:underline">Forgot Password?</a>
        </div>
        <div class="mt-2 text-center text-sm">
            <p class="text-gray-700">Don't have an account?
                <a href="register.php" class="text-yellow-500 hover:underline">Create Account</a>
            </p>
        </div>
    </div>
</body> 
</html>
