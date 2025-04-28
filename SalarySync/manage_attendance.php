<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Attendance</title>
    <link rel="stylesheet" href="homestyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body>
    <div class="container" id="container">
        <nav>
            <div class="logo">
                <img src="logo-no-background.png">
            </div>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <!-- <li><a href="contact.php">Contact Us</a></li> -->
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <br>
<section id="login-section">
        <!-- Form to add attendance -->
        <div class="add-ded">
            <form action="manage_attendance.php" method="POST">
                <h1>Manage Attendance</h1>
                <div class="input-group">
                    <label for="employee_id">Employee ID:</label>
                    <input type="number" id="employee_id" name="employee_id" required oninput="getEmployeeName()">
                </div>
                <div class="input-group">
                    <label for="employee_name">Employee Name:</label>
                    <input type="text" id="employee_name" name="employee_name" readonly>
                </div>
                <div class="input-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="input-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="Paid Leave">Paid Leave</option>
                    </select>
                </div>
                <button type="submit">Add Attendance</button>
            </form>
            </div>
            <br>
            <!-- Month Selector Form -->
            <div class="attendance-container">
                <div class="add-ded">
            <form method="GET" action="manage_attendance.php">
            <h1>View Monthly Attendance</h1>
                <label for="month">Select Month:</label>
                <input type="month" id="month" name="month" value="<?php echo isset($_GET['month']) ? $_GET['month'] : date('Y-m'); ?>" required>
                <br>
                <button type="submit">View Attendance</button>
            </form>
        </div>

        <!-- Attendance Table -->
        
        <div class="attendance-table">
            <!-- Table to display attendance -->
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
// Get selected month, default to current month if not set
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Fetch attendance records for the selected month, ordered by employee ID ascending and date descending
$attendance_sql = "SELECT employees.id, employees.name, attendance.date, attendance.status 
                   FROM attendance 
                   JOIN employees ON attendance.employee_id = employees.id 
                   WHERE DATE_FORMAT(attendance.date, '%Y-%m') = '$selectedMonth' 
                   ORDER BY employees.id ASC, attendance.date DESC";

$attendance_result = $conn->query($attendance_sql);

if ($attendance_result->num_rows > 0) {
    while ($row = $attendance_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No attendance records found for this month.</td></tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
    </section>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $employee_id = $_POST['employee_id'];
        $date = $_POST['date'];
        $status = $_POST['status'];

        $sql = "INSERT INTO attendance (employee_id, date, status) VALUES ('$employee_id', '$date', '$status')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Attendance record added successfully</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
    ?>

    <script>
        function getEmployeeName() {
            var employeeID = document.getElementById('employee_id').value;

            if (employeeID) {
                $.ajax({
                    url: 'get_employee_name.php',
                    type: 'POST',
                    data: {employee_id: employeeID},
                    success: function(data) {
                        document.getElementById('employee_name').value = data;
                    }
                });
            } else {
                document.getElementById('employee_name').value = '';
            }
        }
    </script>
    <footer>
        <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
    </footer>
</body>
</html>
