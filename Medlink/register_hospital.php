<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medlink";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$hospital_id = $_POST['hospital_id'];
$password = $_POST['password'];

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Prepare SQL query to insert hospital data
$sql = "INSERT INTO hospitals (hospital_id, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Bind parameters and execute
$stmt->bind_param("ss", $hospital_id, $hashed_password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Hospital registered successfully.";
    // Redirect to login page or any other page
    header("Location: login_hp.html");
    exit();
} else {
    echo "Error registering hospital.";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
