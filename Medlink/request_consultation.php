<?php
$conn = new mysqli('localhost', 'root', '', 'online_consultation');

$patient_name = $_POST['patient_name'];
$patient_email = $_POST['patient_email'];
$consultation_time = $_POST['consultation_time'];
$doctor_id = $_POST['doctor_id'];

$query = "INSERT INTO consultations (patient_id, doctor_id, consultation_time, status)
          VALUES (
              (SELECT id FROM patients WHERE email='$patient_email'), 
              '$doctor_id', 
              '$consultation_time', 
              'Pending'
          )";

if ($conn->query($query) === TRUE) {
    echo "Consultation requested successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
