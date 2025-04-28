<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'online_consultation');

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM patients WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $_SESSION['patient_email'] = $email;
    header('Location: patient_dashboard.php');
} else {
    echo "Invalid login credentials.";
}

$conn->close();
?>
