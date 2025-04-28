<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Medicine</title>
    <link rel="shortcut icon" href="health-podcast.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <header>
        <div class="container">
            <div class="logo">
            <img src="medlink-high-resolution-logo-transparent.png">
            </div>
        <nav>
            <ul>
                <li><a href="homepage.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>  
                <li><a href="displayapp.php">Appointments</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>

    <section class="login-section">
        <div class="login-container">
        <h2>Order Medicine</h2>
        <form action="submit_order.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
            <label for="medicine_name">Medicine Name:</label>
            <input type="text" id="medicine_name" name="medicine_name" required>
            </div><br>

            <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            </div><br>

            <div class="form-group">
            <label for="prescription">Upload Prescription:</label>
            <input type="file" id="prescription" name="prescription" accept=".jpg, .jpeg, .png, .pdf">
            </div><br>

            <div class="form-group">
            <button type="submit" class="btn">Order Medicine</button>
            </div>
        </form>
        </div>
        </section>
    </main>
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
                echo '<script>alert("Order placed successfully!");</script>';
                // Optionally, redirect to a confirmation page or dashboard
                header("Location: order_confirmation.php");
                exit();
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



        <!-- Footer -->
    <footer>
        <div class="footer-content">
            
            <p>Address: Gunupur, At – Gobriguda, Po- Kharling, Gunupur, Odisha 765022</p>
            <p>Phone: (123) 456-7890</p>
            <p>Email: contactmedlink@gmail.com</p>
            <div class="social-media">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
            </div>
            <div class="news">
                <p>Stay updated with our latest health tips and news.</p>
                <input type="email" placeholder="Enter your email">
                <button>Subscribe</button>
            </div>
        </div>
    </footer>
   
</body>
</html>
