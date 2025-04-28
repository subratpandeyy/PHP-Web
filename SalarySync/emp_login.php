<?php
include 'db.php'; // Include database connection

// Initialize session
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];

    // Simple validation to ensure fields are not empty
    if (empty($employee_id) || empty($name)) {
        $error = "Both fields are required!";
    } else {
        // Query the database to find the employee with matching ID and name
        $sql = "SELECT * FROM employees WHERE id=? AND name=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $employee_id, $name); // Bind the variables
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();

        // If employee exists, log them in
        if ($employee) {
            $_SESSION['employee_id'] = $employee['id']; // Store employee ID in session
            $_SESSION['employee_name'] = $employee['name']; // Store employee name in session

            // Redirect to dashboard or any other page
            header("Location: employee.php");
            exit;
        } else {
            $error = "Invalid Employee ID or Name!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login</title>
    <link rel="stylesheet" href="login-style.css"> <!-- Include your styles -->
</head>
<body>

<div class="login-container">

    <!-- Display error message if any -->
    <?php if (isset($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="emp_login.php" class="login-form">
    <h2>Employee Login</h2>
        <div class="input-group">
        
        <input type="number" id="employee_id" placeholder="Employee Id" name="employee_id" required>
        </div>

        <div class="input-group">
        
        <input type="text" id="name" placeholder="Employee Name" name="name" required>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

<footer>
    <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
</footer>

</body>
</html>
