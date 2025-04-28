<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Login Page</title>
    <link rel="shortcut icon" href="shrt.png">
    <link rel="Stylesheet" href="login-style.css">
</head>

<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="">
            <h2>Login</h2>
            <div class="input-group">
                <!-- <label for="username">Username</label> -->
                <input placeholder="Username" type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <!-- <label for="password">Password</label> -->
                <input placeholder="Password" type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <br>
        <p><a href="change_password.php">Change Password</a></p>
        <!-- <p><a href="create_account.php">Create New ID</a></p> -->

    </div>

    <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['username'] = $username; // Store username in session
            header("Location: home.php"); // Redirect to homepage
            exit();
        } else {
            echo '<script>alert("Invalid password");</script>';
        }
    } else {
        echo '<script>alert("No user found with that username");<script>';
    }

    $stmt->close();
    $conn->close();
}
?>
<footer>
    <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
</footer>
</body>
</html>
