<?php
include 'db.php'; // Include database connection

// Fetch feedback data
$sql = "SELECT user_id, name, email, feedback FROM feedback ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedbacks</title>
    <link rel="stylesheet" href="homestyle.css">
</head>
<body>
    <div class="container" id="container">
        <nav>
            <div class="logo">
                <img src="logo-no-background.png">
            </div>
            <ul>
                <li><a href="emp.html">Home</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <!-- <li><a href="contact.php">Contact Us</a></li> -->
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <br>
    <div class="feedback-list">
        <h1>Submitted Feedback</h1>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='feedback-item'>";
                echo "<p><strong>User ID:</strong> " . htmlspecialchars($row['user_id']) . "</p>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                echo "<p><strong>Feedback:</strong> " . htmlspecialchars($row['feedback']) . "</p>";
                echo "<hr>";
                echo "</div>";
            }
        } else {
            echo "<p>No feedbacks submitted yet.</p>";
        }
        ?>
    </div>
    <footer>
        <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
    </footer>
</body>
</html>
