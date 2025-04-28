<?php
session_start();
include 'db_config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username and Password are required!";
            header("Location: register.php?error=Username and Password are required!");
            exit();
        }
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        if ($stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            $_SESSION['error'] = "An error occurred. Please try again later.";
            header("Location: register.php ?error=An error occurred. Please try again later.");
            exit();
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Username already taken. Please choose another one.";
            header("Location: register.php?error=Username already taken. Please choose another one.");
            exit();
        } else {
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt === false) {
                error_log("Prepare failed: " . $conn->error);
                $_SESSION['error'] = "An error occurred. Please try again later.";
                header("Location: register.php?error=An error occurred. Please try again later.");
                exit();
            }

            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Registration successful! Please login.";
                header("Location: login.html"); 
                exit();
            } else {
                error_log("Error inserting user: " . $stmt->error);
                $_SESSION['error'] = "An error occurred while registering. Please try again.";
                header("Location: register.php?error=An error occurred while registering. Please try again.");
                exit();
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        $_SESSION['error'] = "Username and Password are required!";
        header("Location: register.php?error=Username and Password are required!");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo-no-background.png" type="image">
    <link rel="stylesheet" href="login_page.css">
    <title>Register</title>
    <?php
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_GET['error'])) {
            echo "<p class='error-message'>" . htmlspecialchars($_GET['error']) . "</p>";
        }

        if (isset($_SESSION['message'])) {
            echo "<p class='success-message'>" . htmlspecialchars($_SESSION['message']) . "</p>";
            unset($_SESSION['message']);
        }
        ?>
    <div class="container">
        <div class="icon">
            <img src="logo-no-background.png">
        </div><br>
        <div class="log">Register</div>
            <form action="register.php" method="post">
                    <div class="input-field">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    </div>
                    <p > <a href="login.html">Already have an account? Login</a></p>
                    <br>
                    <button type="submit">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
