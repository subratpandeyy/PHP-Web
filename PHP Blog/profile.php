<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db_config.php';

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_password, $username);
    if ($stmt->execute()) {
        echo "Profile updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <link rel="stylesheet" href="welcome_style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Baskervville+SC&family=League+Spartan:wght@100..900&family=Space+Grotesk:wght@300..700&display=swap"
 rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<header>
    <div class="logo"><a href="index.html">HackForce Academy</a></div>
        <nav>
            <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="#">Offerings</a></li>
                    <li><a href="blog-temp.php">Blog</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
    <aside>
            <ul>
            <h1 class="left-element">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1><br>
                <li><a href="dashboard.php" class="lnk">Dashboard</a></li> 
                <li><a href="create_post.php" class="lnk">Create New Post</a></li>
                <li><a href="view_post.php" class="lnk">View Posts</a></li>
                <li><a href="profile.php" class="lnk">Update Profile</a></li>
                </ul>
    </aside>
    <div class="input">
        <h2>Update Profile</h2>
        <form action="profile.php" method="post">
            <p><b>Username:</b> <?php echo htmlspecialchars($username); ?></p> 
            <br>
            <br>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <br>
            <br>
            <input type="submit" value="Update Profile">
            <div class="back-link">
            <a href="dashboard.php">Back to Welcome Page</a>
            </div>
        </form>
    </div>
    </div>
</body>
</html>