<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php'; // Ensure you have the database connection

// Fetch the total number of employees and paid employees from the database
$paidEmployeesQuery = "SELECT COUNT(DISTINCT employee_id) AS paid_count FROM salarypayments WHERE payment_status = 'Paid'";
$totalEmployeesQuery = "SELECT COUNT(*) AS total_count FROM employees";

$paidResult = $conn->query($paidEmployeesQuery);
$totalResult = $conn->query($totalEmployeesQuery);

$paidEmployees = 0;
$totalEmployees = 0;

if ($paidResult->num_rows > 0) {
    $row = $paidResult->fetch_assoc();
    $paidEmployees = $row['paid_count'];
}

if ($totalResult->num_rows > 0) {
    $row = $totalResult->fetch_assoc();
    $totalEmployees = $row['total_count'];
}

// Unpaid employees calculation
$unpaidEmployees = $totalEmployees - $paidEmployees;

// Handle deduction form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $deduction_type = $_POST['deduction_type'];
    $amount = $_POST['amount'];

    $sql = "INSERT INTO deductions (employee_id, deduction_type, amount) VALUES ('$employee_id', '$deduction_type', '$amount')";
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Bonus added successfully");</script';
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Fetch employee name for the given ID via AJAX
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    $sql = "SELECT name FROM employees WHERE id = '$employee_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['name'];
    } else {
        echo "Employee not found";
    }
    exit(); // Stop the script to prevent the rest of the page from loading
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Salary System</title>
    <link rel="stylesheet" href="homestyle.css">
    <link rel="shortcut icon" href="shrt.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Add Chart.js -->
    <script>
        // Function to fetch employee name when employee ID is provided
        function fetchEmployeeName(employeeId) {
            if (employeeId) {
                // Create a new AJAX request to fetch the employee's name
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '?employee_id=' + employeeId, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        // Update the employee_name field with the returned data
                        document.getElementById('employee_name').value = this.responseText;
                    } else {
                        document.getElementById('employee_name').value = 'Not found';
                    }
                };
                xhr.send();
            }
        }
    </script>
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
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</div>

<br>
<div class="content">
    <div class="tp">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <marquee width="450px"><p>This is the home page, accessible only to logged-in users.</p></marquee>
    </div>
    <br>

    <section id="login-section">
        <div id="opts">
            <a href="add_employee.php">Add Employee</a>
            <a href="view_employees.php">View Employees</a>
            <a href="manage_attendance.php">Attendance</a>
            <a href="view_feedback.php">Feedback</a>
        </div>

        <div class="add-ded">
            <form action="" method="POST">
                <h1>Add Bonus</h1>
                <div class="input-group">
                    <label for="employee_id">Employee ID:</label>
                    <input type="number" id="employee_id" name="employee_id" required onchange="fetchEmployeeName(this.value)">
                </div>
                <div class="input-group">
                    <label for="employee_name">Employee Name:</label>
                    <input type="text" id="employee_name" name="employee_name" readonly>
                </div>
                <div class="input-group">
                    <label for="deduction_type">Bonus Type:</label>
                    <input type="text" id="deduction_type" name="deduction_type" required>
                </div>
                <div class="input-group">
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" step="0.01" required>
                </div>
                <button type="submit">Add Bonus</button>
            </form>
        </div>

        <!-- Chart Section -->
        <div class="chart-container" style="width: 27%; margin: auto; color: #fff; margin-bottom: 2em;">
            <canvas id="paymentChart"></canvas>
        </div>

        <script>
            var ctx = document.getElementById('paymentChart').getContext('2d');
            var paymentChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Paid Employees', 'Unpaid Employees'],
                    datasets: [{
                        label: 'Employee Payment Status',
                        data: [<?php echo $paidEmployees; ?>, <?php echo $unpaidEmployees; ?>],
                        backgroundColor: ['rgb(202, 43, 139)', 'rgb(43, 47, 202)'],
                        borderColor: ['rgb(202, 43, 139)', 'rgb(43, 47, 202)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        </script>
    </section>
</div>
<br><br>
<footer>
        <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
    </footer>
</body>
</html>
