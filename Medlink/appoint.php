<?php
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
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $doctor_type = $_POST['doctor-type'];
    $disease = $_POST['disease'];
    $symptoms = $_POST['symptoms'];

    $sql = "INSERT INTO appointments (name, gender, age, doctor_type, disease, symptoms) 
            VALUES ('$name', '$gender', $age, '$doctor_type', '$disease', '$symptoms')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Appointment booked successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>