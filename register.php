<?php
// Start the session if it hasn't already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the Database class
require_once 'connection.php';

// Initialize the Database class
$db = new Database();

// Initialize an empty error message
$errorMessage = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $foot_size = intval($_POST['foot_size']);
    $gender = htmlspecialchars(trim($_POST['gender']));
    $address = htmlspecialchars(trim($_POST['address']));
    $age = intval($_POST['age']);
    $nic = htmlspecialchars(trim($_POST['nic']));
    $contact = htmlspecialchars(trim($_POST['contact']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = 'customer'; // Default role for new users

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $errorMessage = "Passwords do not match.";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements to prevent SQL injection
        $query = "INSERT INTO users (name, email, foot_size, gender, address, age, nic, contact, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$name, $email, $foot_size, $gender, $address, $age, $nic, $contact, $hashed_password, $role];

        // Execute the query
        if ($db->executeQuery($query, $params)) {
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit;
        } else {
            $errorMessage = "Error: Unable to register. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Craze Kicks</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background: url('images/central-image.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
    <!-- Account Creation Form -->
    <div class="flex flex-grow items-center justify-center px-4">
        <div class="w-full max-w-lg bg-white bg-opacity-75 shadow-lg rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center mb-6">
                <span class="text-green-600">Craze</span> <span class="text-yellow-500">Kicks</span> - Create Your Account
            </h2>

            <!-- Error Message -->
            <?php if (!empty($errorMessage)): ?>
                <div class="mb-4 text-red-600 text-center"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form action="register.php" method="POST" class="space-y-6">
                <!-- Full Name -->
                <div>
                    <label class="block text-gray-700 font-semibold">Full Name</label>
                    <input type="text" name="name" required placeholder="Enter your full name"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-700 font-semibold">Email</label>
                    <input type="email" name="email" required placeholder="Enter your email"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- Foot Size -->
                <div>
                    <label class="block text-gray-700 font-semibold">Foot Size</label>
                    <select name="foot_size" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                        <option value="">Select your foot size</option>
                        <?php for ($i = 6; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-gray-700 font-semibold">Gender</label>
                    <div class="flex space-x-6">
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
                    <label class="block text-gray-700 font-semibold">Address</label>
                    <input type="text" name="address" required placeholder="Enter your address"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- Age -->
                <div>
                    <label class="block text-gray-700 font-semibold">Age</label>
                    <input type="number" name="age" required min="1" max="120" placeholder="Enter your age"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- NIC Number -->
                <div>
                    <label class="block text-gray-700 font-semibold">NIC Number</label>
                    <input type="text" name="nic" required placeholder="Enter your NIC number"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- Contact Number -->
                <div>
                    <label class="block text-gray-700 font-semibold">Contact Number</label>
                    <input type="tel" name="contact" required placeholder="Enter your contact number"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-700 font-semibold">Password</label>
                    <input type="password" name="password" required placeholder="Create a password"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-gray-700 font-semibold">Confirm Password</label>
                    <input type="password" name="confirm_password" required placeholder="Confirm your password"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-all">
                    Create Account
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-gray-700">Already have an account? 
                    <a href="login.php" class="text-yellow-500 hover:underline">Log In</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>