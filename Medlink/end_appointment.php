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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];

    // Prepare SQL statement to delete the appointment
    $sql = "DELETE FROM appointments WHERE id = ?";

    // Use prepared statements for security
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        echo "Appointment ended successfully.";
    } else {
        echo "Error ending appointment: " . $conn->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();

// Redirect back to the appointments page
header("Location: displayapp.php");
exit();
?>
