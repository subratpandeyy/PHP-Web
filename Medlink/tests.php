<?php
// Database connection
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

// Prepare and execute SQL query
$sql = "SELECT t.id, t.test_name, t.test_date, t.result, u.email AS patient_name
        FROM tests t
        JOIN users u ON t.id = u.id";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("SQL query preparation failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Test Results</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <div class="container">
            <div class="logo">
                <img src="medlink-high-resolution-logo-transparent.png" alt="MedLink Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="homepage.html">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="displayapp.php">Appointments</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <h1>Laboratory Test Results</h1>
        
        <table>
            <tr>
                <th>Test ID</th>
                <th>Test Name</th>
                <th>Test Date</th>
                <th>Result</th>
                <th>Patient Name</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["test_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["test_date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["result"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["patient_name"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No test results found</td></tr>";
            }
            
            // Close statement and connection
            $stmt->close();
            $conn->close();
            ?>
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
