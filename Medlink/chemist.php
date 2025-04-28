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

// Fetch all orders with user email
$sql = "SELECT o.order_id, o.medicine_name, o.quantity, o.prescription, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id"; // Adjust the join condition as needed

$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chemist Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="medlink-high-resolution-logo-transparent.png" alt="MedLink Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="orders-section">
            <div class="container">
                <h2>Orders</h2>
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Medicine Name</th>
                                <th>Quantity</th>
                                <th>Email</th>
                                <th>Prescription</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                                    <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <a href="<?= htmlspecialchars($row['prescription']) ?>" target="_blank">View Prescription</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

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

<?php
$conn->close();
?>
