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

$blood_type = $_POST['blood_type'];
$units = $_POST['units'];

$sql = "UPDATE blood_availability SET units_available = ? WHERE blood_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $units, $blood_type);

if ($stmt->execute()) {
    echo "Blood availability updated successfully!";
} else {
    echo "Error updating blood availability: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
