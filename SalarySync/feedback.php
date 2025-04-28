<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $name = $_POST['Name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];

    // Insert feedback into the database
    $sql = "INSERT INTO feedback (user_id, name, email, feedback) VALUES ('$user_id', '$name', '$email', '$feedback')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Feedback submitted successfully'); window.location.href = 'feedback.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback page</title>
    <link rel="stylesheet" href="homestyle.css">
    <link rel="shortcut icon" href="shrt.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container" id="container">
        <nav>
            <div class="logo">
                <img src="logo-no-background.png">
            </div>
            <ul>
                <li><a href="employee.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <br>
    <br>
    <br>
    <div class="cont-body">
        <div class="contact-form">
            <h1>Feedback Form</h1>
            <form method="POST" action="feedback.php">
                <div class="input-group">
                    <label for="user_id">User ID</label>
                    <input type="number" id="user_id" name="user_id" required placeholder="Enter your user ID">
                </div>
                <div class="input-group">
                    <label for="Name">Name</label>
                    <input type="text" id="Name" name="Name" required placeholder="Enter your name">
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required placeholder="Enter your email">
                </div>
                <div class="input-group">
                    <label for="feedback">Feedback</label>
                    <textarea name="feedback" placeholder="Write your feedback here..." required></textarea>
                </div>
                <div class="submit">
                    <button type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
    </footer>
</body>
</html>
