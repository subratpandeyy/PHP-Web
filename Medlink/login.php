<?php
// Start the session
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root"; // replace with your MySQL username
$password = "";     // replace with your MySQL password
$dbname = "medlink";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to fetch the user's details
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Redirect to the homepage
            header("Location: homepage.html");
            exit();
        } else {
            // Incorrect password
            echo "Invalid email or password.";
        }
    } else {
        // Email not found
        echo "Invalid email or password.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
