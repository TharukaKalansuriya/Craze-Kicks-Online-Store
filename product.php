<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "crazekicks";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product ID safely
$itemId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch item details
$sql = "SELECT * FROM items WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    die("<h1 class='text-center text-red-500 mt-10'>Product not found</h1>");
}

// Decode images
$images = json_decode($item['image'], true);
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($item['name']); ?> - Craze Kicks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mx-auto p-4 content">
    <div class="flex flex-col md:flex-row space-x-0 md:space-x-6">
        <div class="md:w-1/2">
            <!-- Bootstrap Carousel -->
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-100 h-96 object-cover rounded-lg shadow-lg" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="flex flex-col space-y-4 md:w-1/2">
            <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($item['name']); ?></h1>
            <p class="text-gray-700"><?php echo htmlspecialchars($item['description']); ?></p>
            <p class="text-green-600 text-xl font-semibold">$<?php echo number_format($item['price'], 2); ?></p>

            <div>
                <label for="size" class="font-semibold block">Select Size:</label>
                <select id="size" name="size" class="border rounded p-2 w-full">
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                    <option>11</option>
                </select>
            </div>

            <div>
                <label for="quantity" class="font-semibold block">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" class="border rounded p-2 w-20">
            </div>

            <div class="flex space-x-4">
            <form action="addwishlist.php" method="POST">
            <input type="hidden" name="itemID" value="<?php echo $item['id']; ?>">
             <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                 Add to Wish List
             </button>
            </form>


                <button onclick="addToCart(<?php echo $item['id']; ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Add to Cart
                </button>
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Purchase
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function addToCart(itemId) {
        const size = document.getElementById("size").value;
        const quantity = document.getElementById("quantity").value;

        fetch("add_to_cart.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: itemId, size: size, quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Added to cart!");
            } else {
                alert("Failed to add to cart. Please try again.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        });
    }
</script>

<?php include 'footer.php'; ?>

</body>
</html>

<?php
$conn->close();
?>
