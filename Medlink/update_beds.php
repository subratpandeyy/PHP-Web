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

$total_beds = $_POST['total_beds'];
$occupied_beds = $_POST['occupied_beds'];
$bed_type = $_POST['bed_type'];

$sql = "UPDATE bed_availability SET total_beds = ?, occupied_beds = ? WHERE bed_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $total_beds, $occupied_beds, $bed_type);

if ($stmt->execute()) {
    echo "Bed availability updated successfully!";
} else {
    echo "Error updating bed availability: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
