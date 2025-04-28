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

$specialization = $_POST['specialization'];
$available_doctors = $_POST['available_doctors'];
$unavailable_doctors = $_POST['unavailable_doctors'];

// Update available doctors
$sql = "UPDATE doctor_availability SET available_doctors = ?, unavailable_doctors = ? WHERE specialization = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $available_doctors, $unavailable_doctors, $specialization);

if ($stmt->execute()) {
    echo "Doctor availability updated successfully!";
} else {
    echo "Error updating doctor availability: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
