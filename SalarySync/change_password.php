<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="login-style.css">
</head>
<body>
<div class="login-container">
<form action="change_password.php" method="POST" class="login-form">
    <h2>Change Password</h2>
        <div class="input-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
        </div>
        <div class="input-group">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        </div>
        <button type="submit">Change Password</button>
    </form>
</div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'db.php';

        $username = $_POST['username'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($current_password, $user['password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
                if ($conn->query($update_sql) === TRUE) {
                    echo "<p>Password changed successfully</p>";
                } else {
                    echo "<p>Error: " . $update_sql . "<br>" . $conn->error . "</p>";
                }
            } else {
                echo "<p>Invalid current password</p>";
            }
        } else {
            echo "<p>No user found with that username</p>";
        }

        $conn->close();
    }
    ?>
</body>
</html>
