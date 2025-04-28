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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $hospital_id = $_POST['hospital_id'];

    // Prepare SQL statement (Update 'id' to your actual column name)
    $sql = "SELECT * FROM hospitals WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param('s', $hospital_id);

    // Execute the statement
    $stmt->execute();

    // Check results
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Login successful
        header("Location: hospital_home.html");
        exit();
    } else {
        // Login failed
        echo "Invalid Hospital ID.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
