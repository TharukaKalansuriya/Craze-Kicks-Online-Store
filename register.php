<?php
session_start();
$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = "1234"; // Your database password
$dbname = "crazekicks"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize an empty error message
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $foot_size = $_POST['foot_size'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $nic = $_POST['nic'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'customer';

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errorMessage = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (name, email, foot_size, gender, address, age, nic, contact, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisssisss", $name, $email, $foot_size, $gender, $address, $age, $nic, $contact, $hashed_password, $role);

        if ($stmt->execute()) {
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit;
        } else {
            $errorMessage = "Error: " . $stmt->error;
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
    <title>Create Account - Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <!-- Account Creation Container -->
    <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-6">Create Your Account</h2>

        <!-- Display error message -->
        <?php if (!empty($errorMessage)): ?>
            <div class="mb-4 text-red-600 text-center"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Create Account Form -->
        <form action="register.php" method="POST" class="space-y-6">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-gray-700 font-semibold">Full Name</label>
                <input type="text" id="name" name="name" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your full name">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-semibold">Email</label>
                <input type="email" id="email" name="email" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your email">
            </div>

            <!-- Foot Size -->
            <div>
                <label for="foot-size" class="block text-gray-700 font-semibold">Foot Size</label>
                <select id="foot-size" name="foot_size" required 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Select your foot size</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-gray-700 font-semibold">Gender</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input type="radio" name="gender" value="male" required class="mr-2">
                        Male
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="gender" value="female" required class="mr-2">
                        Female
                    </label>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-gray-700 font-semibold">Address</label>
                <input type="text" id="address" name="address" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your address">
            </div>

            <!-- Age -->
            <div>
                <label for="age" class="block text-gray-700 font-semibold">Age</label>
                <input type="number" id="age" name="age" required min="1" max="120" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your age">
            </div>

            <!-- NIC Number -->
            <div>
                <label for="nic" class="block text-gray-700 font-semibold">NIC Number</label>
                <input type="text" id="nic" name="nic" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your NIC number">
            </div>

            <!-- Contact Number -->
            <div>
                <label for="contact" class="block text-gray-700 font-semibold">Contact Number</label>
                <input type="tel" id="contact" name="contact" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Enter your contact number">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-700 font-semibold">Password</label>
                <input type="password" id="password" name="password" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Create a password">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="confirm-password" class="block text-gray-700 font-semibold">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm_password" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" 
                       placeholder="Confirm your password">
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 focus:outline-none">
                Create Account
            </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <p class="text-gray-700">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Log In</a></p>
        </div>
    </div>
</body>
</html>
