<?php
// Database connection
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

// SQL query to fetch appointment details
$sql = "SELECT a.id AS appointment_id, a.name AS patient_name, a.gender, a.age, a.disease, a.symptoms, a.appointment_date,
               d.name AS doctor_name, d.weekdays, d.availability_start_time, d.availability_end_time
        FROM appointments a
        LEFT JOIN doctors d ON a.doctor_id = d.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Details</title>
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

    <main>
        <h1>Appointment Details</h1>
        
        <table>
            <tr>
                <th>Appointment ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Disease</th>
                <th>Symptoms</th>
                <th>Doctor Name</th>
                <th>Weekdays</th>
                <th>Start Time</th> <!-- Column for doctor's start time -->
                <th>End Time</th> <!-- Column for doctor's end time -->
                <th>Appointment Date</th> <!-- Modified column for appointment date -->
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $weekdays = isset($row['weekdays']) ? htmlspecialchars($row['weekdays']) : 'Not Available';
                    $appointment_date = isset($row['appointment_date']) ? htmlspecialchars($row['appointment_date']) : 'Not Available';
                    $availability_start_time = isset($row['availability_start_time']) ? htmlspecialchars($row['availability_start_time']) : 'Not Available';
                    $availability_end_time = isset($row['availability_end_time']) ? htmlspecialchars($row['availability_end_time']) : 'Not Available';

                    // Remove booking timing from the appointment date
                    // Assuming you want to display just the date without the time
                    $appointment_date_only = date('Y-m-d', strtotime($appointment_date));

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["appointment_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["patient_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["age"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["disease"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["symptoms"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["doctor_name"]) . "</td>";
                    echo "<td>" . $weekdays . "</td>";
                    echo "<td>" . $availability_start_time . "</td>"; // Display start time
                    echo "<td>" . $availability_end_time . "</td>"; // Display end time
                    echo "<td>" . $appointment_date_only . "</td>"; // Display only date
                    echo "<td>
                            <form method='POST' action='end_appointment.php'>
                                <input type='hidden' name='appointment_id' value='" . htmlspecialchars($row["appointment_id"]) . "'>
                                <input type='submit' value='End Appointment'>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No appointments found</td></tr>";
            }

            $conn->close();
            ?>
        </table>
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
