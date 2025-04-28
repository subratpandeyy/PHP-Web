<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Doctor Availability</title>
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

    <main>
        <h1>Doctors List</h1>
        
        <table>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Department</th>
                <th>Hospital</th>
                <th>Available from</th>
                <th>Available till</th>
            </tr>
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

            // Prepare SQL statement to fetch doctors with department and hospital information
            $sql = "SELECT doctors.id, doctors.name, departments.name AS department_name, hospitals.hospital_name AS hospital_name, doctors.availability_start_time, doctors.availability_end_time
                    FROM doctors
                    JOIN departments ON doctors.department_id = departments.id
                    JOIN hospitals ON doctors.hospital_id = hospitals.id";

            // Execute the query
            $result = $conn->query($sql);

            if ($result === false) {
                // Display SQL error if the query fails
                die("Query failed: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["department_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["hospital_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["availability_start_time"]) . " AM" . "</td>";
                    echo "<td>" . htmlspecialchars($row["availability_end_time"]) . " PM" . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No doctors found</td></tr>";
            }

            // Close connection
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
