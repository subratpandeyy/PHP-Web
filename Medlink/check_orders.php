// check_orders.php
<?php
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

// Fetch new orders
$sql = "SELECT * FROM orders WHERE status = 'pending' ORDER BY order_date DESC";
$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

echo json_encode($orders);

$conn->close();
?>