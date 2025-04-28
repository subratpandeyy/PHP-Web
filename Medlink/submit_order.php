<?php
session_start();
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in
    $medicine_name = $_POST['medicine_name'];
    $quantity = $_POST['quantity'];
    
    // Handle file upload
    $prescription = $_FILES['prescription']['name'];
    $target_dir = "uploads/prescriptions/";
    $target_file = $target_dir . time() . '_' . basename($prescription);

    // Check if the directory exists, and create it if it doesn't
    if (!is_dir($target_dir)) {
        if (!mkdir($target_dir, 0777, true)) {
            die("Failed to create directory: " . $target_dir);
        }
    }

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file type
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'pdf'])) {
        echo "Sorry, only JPG, JPEG, PNG & PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["prescription"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // If everything is ok, upload file
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["prescription"]["tmp_name"], $target_file)) {
            // Insert order into the database
            $sql = "INSERT INTO orders (user_id, medicine_name, quantity, prescription) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isis", $user_id, $medicine_name, $quantity, $target_file);

            if ($stmt->execute()) {
                echo "Order placed successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
