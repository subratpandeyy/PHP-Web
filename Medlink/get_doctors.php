<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$specialization = $_GET['specialization'];
$hospital_name = $_GET['hospital_name'];

$sql = "SELECT name FROM doctors WHERE specialization = ? AND hospital_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $specialization, $hospital_name);
$stmt->execute();
$result = $stmt->get_result();

$doctors = array();
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row['name'];
}

echo json_encode($doctors);

$stmt->close();
$conn->close();
?>
