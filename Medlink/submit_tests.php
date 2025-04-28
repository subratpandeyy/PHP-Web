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

$patient_id = $_POST['patient_id'];
$test_type = $_POST['test_type'];
$test_result = $_POST['test_result'];
$test_date = $_POST['test_date'];

$sql = "INSERT INTO test_results (patient_id, test_type, test_result, test_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $patient_id, $test_type, $test_result, $test_date);

if ($stmt->execute()) {
    echo "Doctor availability updated successfully!";
} else {
    echo "Error updating doctor availability: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
