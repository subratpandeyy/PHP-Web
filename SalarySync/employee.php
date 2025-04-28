<?php
include 'db.php'; // Include the database connection file

// Initialize variables for employee data and payment information
$employeeData = null;
$errorMsg = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted form data
    $employee_id = $_POST['id'];
    $employee_name = $_POST['name'];

    // Prepare the SQL query to fetch employee and salary data
    $sql = "SELECT employees.id, employees.name, employees.position, employees.email, 
            salarypayments.payment_id, salarypayments.payment_date, salarypayments.paid_amount, salarypayments.payment_status 
            FROM employees 
            LEFT JOIN salarypayments ON employees.id = salarypayments.employee_id 
            WHERE employees.id = ? AND employees.name = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters to the SQL query
        $stmt->bind_param("is", $employee_id, $employee_name);
        $stmt->execute();
        $stmt->store_result();

        // Check if any result is found
        if ($stmt->num_rows > 0) {
            // Bind the results to variables
            $stmt->bind_result($id, $name, $position, $email, $payment_id, $payment_date, $paid_amount, $payment_status);
            $employeeData = [];
            while ($stmt->fetch()) {
                $employeeData[] = [
                    'position' => $position,
                    'email' => $email,
                    'payment_id' => $payment_id,
                    'payment_date' => $payment_date,
                    'paid_amount' => $paid_amount,
                    'payment_status' => $payment_status
                ];
            }
        } else {
            $errorMsg = "No employee found with the given ID and Name.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $errorMsg = "Error preparing SQL statement: " . htmlspecialchars($conn->error);
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Data</title>
    <link rel="stylesheet" href="homestyle.css">
</head>
<body>
    <div class="container" id="container">
        <nav>
            <div class="logo">
                <img src="logo-no-background.png">
            </div>
            <ul>
                <li><a href="employee.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <br>
    <?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: emp_login.php");
    exit;
}

// Welcome the employee
echo "<h1>Welcome, " . htmlspecialchars($_SESSION['employee_name']) . "!</h1>";

?>

    <section class="login-section">
        <div class="container-user">
            <h1>Employee Data</h1>
            <form action="" method="POST">
                <div class="input-group">
                    <label for="id">Employee ID:</label>
                    <input type="number" name="id" placeholder="Enter Employee ID" required>
                </div>
                <br>
                <div class="input-group">
                    <label for="name">Employee Name:</label>
                    <input type="text" name="name" placeholder="Enter Employee Name" required>
                </div>
                <input type="submit" value="Search">
            </form>

            <?php if (!empty($employeeData)): ?>
                <div class="result">
                    <?php foreach ($employeeData as $data): ?>
                        <p><span>Position:</span> <?= htmlspecialchars($data['position']); ?></p>
                        <p><span>Email:</span> <?= htmlspecialchars($data['email']); ?></p>

                        <?php if ($data['payment_id'] !== null): ?>
                            <p><span>Payment ID:</span> <?= htmlspecialchars($data['payment_id']); ?></p>
                            <p><span>Payment Date:</span> <?= htmlspecialchars($data['payment_date']); ?></p>
                            <p><span>Paid Amount:</span> Rs.<?= htmlspecialchars($data['paid_amount']); ?></p>
                            <p><span>Payment Status:</span> <?= htmlspecialchars($data['payment_status']); ?></p>
                        <?php else: ?>
                            <p>No payment records found for this employee.</p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php elseif (!empty($errorMsg)): ?>
                <p><?= htmlspecialchars($errorMsg); ?></p>
            <?php endif; ?>

            <!-- Feedback button that redirects to the feedback page -->
            <a href="feedback.php" class="feedback-btn">Submit Feedback</a>
        </div>
    </section>
    <br><br>
    <footer>
        <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
    </footer>
</body>
</html>
