<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sales_keeper') {
    header("Location: login.php");
    exit;
}
?>

<h1>Welcome to the Sales Keeper Dashboard, <?php echo $_SESSION['name']; ?>!</h1>
<!-- Sales keeper-specific functionality here -->
