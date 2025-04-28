<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medlink";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM chemists WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($chemist_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['chemist_id'] = $chemist_id;
            header("Location: chemist.php");
            exit();
        } else {
            $error = "Invalid login credentials.";
        }
    } else {
        $error = "Invalid login credentials.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chemist Login</title>
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
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="order-medicine.html">Order Medicine</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="container">
        <h2>Chemist Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login-chemist.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>&copy; 2024 MedLink. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
