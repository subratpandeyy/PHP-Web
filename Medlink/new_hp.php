<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hospital</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navigation Bar -->
    <header>
        <div class="container">
            <div class="logo">
                <img src="medlink-high-resolution-logo-transparent.png" alt="MedLink Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="homepage.html">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="#">Appointments</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Section -->
    <main>
        <section class="login-section">
            <div class="login-container">
                <h2>Add a New Hospital</h2>
                <?php
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "medlink";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Get form data
                    $hospital_name = $_POST['hospital_name'];
                    $location = $_POST['location'];
                    $phone = $_POST['phone'];
                    
                    // Check if 'email' is set and not empty
                    if (isset($_POST['email']) && !empty($_POST['email'])) {
                        $email = $_POST['email'];
                    } else {
                        $email = null; // Handle this as you see fit, e.g., default value or error message
                    }
                
                    // Insert hospital into the database
                    $sql = "INSERT INTO hospitals (hospital_name, address, phone, email) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                
                    if ($stmt === false) {
                        die("Error preparing the statement: " . $conn->error);
                    }
                
                    // Bind parameters and execute
                    $stmt->bind_param("ssss", $hospital_name, $location, $phone, $email);
                
                    if ($stmt->execute()) {
                        echo "<p>Hospital added successfully!</p>";
                    } else {
                        echo "<p>Error: " . $stmt->error . "</p>";
                    }
                
                    // Close statement
                    $stmt->close();
                }
                ?>
                
                <form action="" method="post">
    <div class="form-group">
        <label for="hospital_name">Hospital Name</label>
        <input type="text" id="hospital_name" name="hospital_name" required>
    </div>
    <br>
    <div class="form-group">
        <label for="location">Address</label>
        <input type="text" id="location" name="location" required>
    </div>
    <br>
    <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" required>
    </div>
    <br>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <br>
    <div class="form-group">
        <button type="submit" class="btn">Add Hospital</button>
    </div>
</form>

            </div>
        </section>
    </main>

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
        </div>
    </footer>

</body>
</html>
