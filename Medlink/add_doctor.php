<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor</title>
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
                    <li><a href="displayapp.php">Appointments</a></li>
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
                <h2>Add a New Doctor</h2>
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
                    $doctor_id = $_POST['doctor_id'];
                    $doctor_name = $_POST['doctor_name'];
                    $department_id = $_POST['department_id'];
                    $hospital_id = $_POST['hospital_id'];
                    $availability_start_time = $_POST['availability_start_time'];
                    $availability_end_time = $_POST['availability_end_time'];

                    // Insert doctor into the database
                    $sql = "INSERT INTO doctors (id, name, department_id, hospital_id, availability_start_time, availability_end_time) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt === false) {
                        die("Error preparing the statement: " . $conn->error);
                    }

                    // Bind parameters and execute
                    $stmt->bind_param("ississ", $doctor_id, $doctor_name, $department_id, $hospital_id, $availability_start_time, $availability_end_time);

                    if ($stmt->execute()) {
                        echo "<p>Doctor added successfully!</p>";
                    } else {
                        echo "<p>Error: " . $stmt->error . "</p>";
                    }

                    // Close statement
                    $stmt->close();
                }

                ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="doctor_id">Doctor ID</label>
                        <input type="number" id="doctor_id" name="doctor_id" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="doctor_name">Doctor Name</label>
                        <input type="text" id="doctor_name" name="doctor_name" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="hospital_id">Hospital</label>
                        <select id="hospital_id" name="hospital_id" required>
                            <?php
                            // Fetch hospitals from the database
                            $hospital_query = "SELECT id, hospital_name FROM hospitals";
                            $result = $conn->query($hospital_query);

                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="'.$row['id'].'">'.$row['hospital_name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    
                    <div class="form-group">
                        <label for="department_id">Department</label>
                        <select id="department_id" name="department_id" required>
                            <?php
                            $department_query = "SELECT id, name FROM departments";
                            $result = $conn->query($department_query);

                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="availability_start_time">Availability Start Time</label>
                        <input type="time" id="availability_start_time" name="availability_start_time" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="availability_end_time">Availability End Time</label>
                        <input type="time" id="availability_end_time" name="availability_end_time" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Add Doctor</button>
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
