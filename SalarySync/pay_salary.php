<?php
include 'db.php'; // Include the database connection

// Check if employee ID is passed via GET
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Fetch employee details
    $sql = "SELECT * FROM employees WHERE id='$employee_id'";
    $result = $conn->query($sql);
    $employee = $result->fetch_assoc();

    if (!$employee) {
        echo "Employee not found";
        exit;
    }

    // Fetch total deductions for the employee
    $deduction_sql = "SELECT SUM(amount) AS total_deductions FROM deductions WHERE employee_id='$employee_id'";
    $deduction_result = $conn->query($deduction_sql);
    $deductions_row = $deduction_result->fetch_assoc();
    $total_deductions = $deductions_row['total_deductions'] ? $deductions_row['total_deductions'] : 0;
} else {
    echo "No employee ID provided";
    exit;
}

// Process the form when salary is paid
if (isset($_POST['pay_salary'])) {
    $updated_salary = $_POST['updated_salary'];
    $employee_id = $_POST['employee_id']; // Fetch employee ID from the hidden input field
    $payment_date = date('Y-m-d'); // Get current date

    // Update the salary in the employees table
    $update_salary_sql = "UPDATE employees SET salary='$updated_salary' WHERE id='$employee_id'";
    if ($conn->query($update_salary_sql) === TRUE) {
        // Insert salary payment record in salarypayments table
        $insert_payment_sql = "INSERT INTO salarypayments (employee_id, payment_date, paid_amount, payment_status)
                               VALUES ('$employee_id', '$payment_date', '$updated_salary', 'Paid')";

        if ($conn->query($insert_payment_sql) === TRUE) {
            echo '<script>alert("Salary updated and payment record added successfully.");</script>';
        } else {
            echo "Error recording payment: " . $conn->error;
        }
    } else {
        echo "Error updating salary: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Salary</title>
    <link rel="stylesheet" href="homestyle.css">
</head>
<body>

<h1>Pay Salary for <?php echo htmlspecialchars($employee['name']); ?></h1>

<div class="pay-salary-container">

    <!-- Display employee's current salary and deduction information -->
    <div class="salary-details">
        <p><strong>Current Salary:</strong> Rs. <?php echo htmlspecialchars($employee['salary']); ?></p>
        <p><strong>Total Bonuses:</strong> Rs. <?php echo number_format($total_deductions, 2); ?></p>
        <p><strong>Net Payable Salary:</strong> Rs. <?php echo number_format($employee['salary'] + $total_deductions, 2); ?></p>
        <p><strong>Position:</strong> <?php echo htmlspecialchars($employee['position']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['email']); ?></p>
    </div>

    <!-- Form to pay the salary -->
    <form method='POST' id="pay" action='pay_salary.php?employee_id=<?php echo htmlspecialchars($employee['id']); ?>'>
        <input type='hidden' name='employee_id' value='<?php echo htmlspecialchars($employee['id']); ?>'> <!-- Hidden employee ID -->
        <label for='updated_salary'>Updated Salary Amount:</label>
        <input type='number' id='updated_salary' name='updated_salary' value='<?php echo htmlspecialchars($employee['salary']); ?>' required>
        <button type='submit' name='pay_salary'>Pay Salary</button>
    </form>

</div>

<footer class="footer">
    <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
</footer>

</body>
</html>
