<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medlink";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and order details are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['order_id'])) {
    // Redirect to the home page if no order details found
    header("Location: index.html");
    exit();
}

$order_id = $_SESSION['order_id'];
$user_id = $_SESSION['user_id'];

// Fetch the order details from the database
$sql = "SELECT medicine_name, quantity, prescription FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the order exists
if ($result->num_rows === 0) {
    echo "Order not found.";
    exit();
}

$order = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <img src="medlink-high-resolution-logo-transparent.png">
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="view_orders.php">Your Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

<main>
    <div class="container">
        <h2>Order Confirmation</h2>
        <p><strong>Medicine Name:</strong> <?php echo htmlspecialchars($order['medicine_name']); ?></p>
        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($order['quantity']); ?></p>
        <p><strong>Prescription:</strong> <a href="<?php echo htmlspecialchars($order['prescription']); ?>" target="_blank">View Prescription</a></p>
        <a href="index.html" class="btn">Back to Home</a>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2024 MedLink. All rights reserved.</p>
    </div>
</footer>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
