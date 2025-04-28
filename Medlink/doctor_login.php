<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'online_consultation');

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM doctors WHERE email='$email' AND password='$password'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $_SESSION['doctor_email'] = $email;
    header('Location: doctor_dashboard.php');
} else {
    echo "Invalid login credentials.";
}

$conn->close();
?>
