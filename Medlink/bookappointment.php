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

// Fetch hospitals
$hospital_query = "SELECT id, hospital_name FROM hospitals";
$hospital_result = $conn->query($hospital_query);

// Fetch departments
$department_query = "SELECT id, name FROM departments";
$department_result = $conn->query($department_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $doctor_id = $_POST['doctor_id'];
    $disease = $_POST['disease'];
    $symptoms = $_POST['symptoms'];
    $hospital_id = $_POST['hospital_id'];
    $appointment_date = $_POST['appointment_date'];

    $sql = "INSERT INTO appointments (name, gender, age, doctor_id, disease, symptoms, hospital_id, appointment_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssisssss", $name, $gender, $age, $doctor_id, $disease, $symptoms, $hospital_id, $appointment_date);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle fetching doctors based on department and hospital
if (isset($_GET['fetch_doctors'])) {
    $department_id = intval($_GET['department_id']);
    $hospital_id = intval($_GET['hospital_id']);

    $sql = "SELECT id, name FROM doctors WHERE department_id = ? AND hospital_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $department_id, $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
        }
    } else {
        echo "<option value=''>No doctors available</option>";
    }

    $stmt->close();
}

// Fetch booked doctors
$booked_doctors_query = "SELECT DISTINCT doctor_id FROM appointments";
$booked_doctors_result = $conn->query($booked_doctors_query);

$booked_doctors = [];
if ($booked_doctors_result->num_rows > 0) {
    while ($row = $booked_doctors_result->fetch_assoc()) {
        $booked_doctors[] = $row['doctor_id'];
    }
}

// Prepare SQL statement to fetch doctors with department and hospital information
$sql = "SELECT doctors.id, doctors.name, departments.name AS department_name, hospitals.hospital_name AS hospital_name, doctors.gender, doctors.availability_start_time, doctors.availability_end_time, doctors.weekdays
        FROM doctors
        JOIN departments ON doctors.department_id = departments.id
        JOIN hospitals ON doctors.hospital_id = hospitals.id";

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="health-podcast.png">
    <script>
        function fetchDoctors() {
            var department_id = document.getElementById('department').value;
            var hospital_id = document.getElementById('hospital').value;

            if (department_id && hospital_id) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '?fetch_doctors=1&department_id=' + department_id + '&hospital_id=' + hospital_id, true);
                xhr.onload = function() {
                    if (this.status === 200) {
                        document.getElementById('doctor_id').innerHTML = this.responseText;
                    }
                };
                xhr.send();
            } else {
                document.getElementById('doctor_id').innerHTML = '<option value="">Select Doctor</option>';
            }
        }
    </script>
    <style>
        .booked {
            color: red;
        }
    </style>
</head>
<body>
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

    <main>
        <section class="login-section">
            <div class="login-container">
                <form action="" method="post">
                    <div class="form-group">
                        <h2>Make an appointment!</h2>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div><br>

                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <div class="rad">
                            <input type="radio" id="male" name="gender" value="male">
                            <label for="male">Male</label><br>

                            <input type="radio" id="female" name="gender" value="female">
                            <label for="female">Female</label><br>

                            <input type="radio" id="other" name="gender" value="other">
                            <label for="other">Others</label><br>
                        </div>
                    </div><br>

                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" min="0" required>
                    </div><br>

                    <div class="form-group">
                        <label for="hospital">Select Hospital:</label>
                        <select id="hospital" name="hospital_id" onchange="fetchDoctors()" required>
                            <?php
                            if ($hospital_result->num_rows > 0) {
                                while ($row = $hospital_result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['hospital_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div><br>

                    <div class="form-group">
                        <label for="department">Select Department:</label>
                        <select id="department" name="department_id" onchange="fetchDoctors()" required>
                            <?php
                            if ($department_result->num_rows > 0) {
                                while ($row = $department_result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div><br>

                    <div class="form-group">
                        <label for="doctor_id">Select Doctor:</label>
                        <select id="doctor_id" name="doctor_id" required>
                            <option value="">Select Doctor</option>
                        </select>
                    </div><br>

                    <div class="form-group">
                        <label for="appointment_date">Appointment Date:</label>
                        <input type="date" id="appointment_date" name="appointment_date" required>
                    </div><br>

                    <div class="form-group">
                        <label for="disease">Previous Disease:</label>
                        <input type="text" id="disease" name="disease">
                    </div><br>

                    <div class="form-group">
                        <label for="symptoms">Symptoms:</label>
                        <input type="text" id="symptoms" name="symptoms">
                    </div><br>

                    <div class="form-group">
                        <button type="submit" class="btn">Submit</button>
                    </div>
                </form>
            </div>

            <div class="login_container">
                <h1>Doctors List</h1>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Hospital</th>
                        <th>Available from</th>
                        <th>Available till</th>
                        <th>Weekdays</th>
                    </tr>
                    <?php
                    // Prepare SQL statement to fetch doctor details including weekdays
                    $sql = "SELECT d.id, d.name, d.weekdays, dept.name AS department_name, h.hospital_name, d.availability_start_time, d.availability_end_time
                            FROM doctors d
                            LEFT JOIN departments dept ON d.department_id = dept.id
                            LEFT JOIN hospitals h ON d.hospital_id = h.id";

                    // Execute the query
                    $result = $conn->query($sql);

                    // Check for query errors
                    if ($result === false) {
                        echo "<tr><td colspan='7'>Error: " . $conn->error . "</td></tr>";
                    } else if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            $weekdays = isset($row['weekdays']) ? htmlspecialchars($row['weekdays']) : 'Not Available';
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["department_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["hospital_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["availability_start_time"]) . " AM" . "</td>";
                            echo "<td>" . htmlspecialchars($row["availability_end_time"]) . " PM" . "</td>";
                            echo "<td>" . $weekdays . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No doctors found</td></tr>";
                    }

                    // Close connection
                    $conn->close();
                    ?>
                </table>
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
            <div class="news">
                <p>Stay updated with our latest health tips and news.</p>
                <input type="email" placeholder="Enter your email">
                <button>Subscribe</button>
            </div>
        </div>
    </footer>
</body>
</html>
