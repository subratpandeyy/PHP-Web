<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medlink";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$alert_type = $_POST['alert_type'];
$alert_details = $_POST['alert_details'];

$sql = "INSERT INTO emergency_alerts (alert_type, alert_details) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $alert_type, $alert_details);

if ($stmt->execute()) {
    echo "Emergency alert sent successfully!";
} else {
    echo "Error sending emergency alert: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
