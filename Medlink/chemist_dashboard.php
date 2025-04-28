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

// Fetch new orders
$sql = "SELECT * FROM orders WHERE status = 'pending' ORDER BY order_date DESC";
$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chemist Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function checkNewOrders() {
            // Use AJAX to periodically check for new orders
            setInterval(function() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'check_orders.php', true);
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        var newOrders = JSON.parse(xhr.responseText);
                        if (newOrders.length > 0) {
                            alert('New order received! Medicine: ' + newOrders[0].medicine_name + ', Quantity: ' + newOrders[0].quantity);
                            // Reload the page or update the dashboard
                            location.reload();
                        }
                    }
                };
                xhr.send();
            }, 10000); // Check every 10 seconds
        }

        window.onload = checkNewOrders;
    </script>
</head>
<body>

<!-- Navigation Bar -->
<header>
        <div class="container">
            <div class="logo">
            <img src="medlink-high-resolution-logo-transparent.png">
            </div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">Login</a>
                    <div class="dropdown-content">
                        <a href="login-user.html">User Login</a>
                        <a href="login-hp.html">Hospital login</a>
                        <a href="index.html#email">New User</a>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Chemist Dashboard</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Prescription</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo $order['medicine_name']; ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><a href="<?php echo $order['prescription']; ?>" target="_blank">View Prescription</a></td>
                    <td><?php echo $order['status']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </main>

         <!-- Footer -->
    <footer>
        <div class="footer-content">
            
            <p>Address: Gunupur, At – Gobriguda, Po- Kharling, Gunupur, Odisha 765022</p>
            <p>Phone: (123) 456-7890</p>
            <p>Email: contactmedlink@gmail.com</p>
            <div class="social-media">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
            </div>
            <div class="news">
                <p>Stay updated with our latest health tips and news.</p>
                <input type="email" placeholder="Enter your email">
                <button>Subscribe</button>
            </div>
        </div>
    </footer>
</body>
</html>