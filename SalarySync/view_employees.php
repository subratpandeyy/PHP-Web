<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Employees</title>
    <link rel="shortcut icon" href="shrt.png">
    <link rel="stylesheet" href="homestyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container" id="container">
    <nav>
        <div class="logo">
            <img src="logo-no-background.png">
        </div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="">Services</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</div>

<h1>Employee List</h1>

<!-- Filter Dropdown for Paid/Unpaid Employees -->
 <div class="dd">
<form method="GET" action="">
    <label for="filter">Filter by Payment Status:</label>
    <select name="filter" id="filter" onchange="this.form.submit()">
        <option value="all" <?php if (!isset($_GET['filter']) || $_GET['filter'] == 'all') echo 'selected'; ?>>Both</option>
        <option value="paid" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'paid') echo 'selected'; ?>>Paid</option>
        <option value="unpaid" <?php if (isset($_GET['filter']) && $_GET['filter'] == 'unpaid') echo 'selected'; ?>>Unpaid</option>
    </select>
</form>
</div>

<!-- Employee Table -->
<table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Position</th>
        <th>Salary</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php
    // Default filter is to show all employees
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

    // Adjust SQL query based on the selected filter
    if ($filter == 'paid') {
        $sql = "SELECT e.* FROM employees e 
                JOIN salarypayments sp ON e.id = sp.employee_id
                WHERE sp.payment_status = 'Paid'
                GROUP BY e.id"; // Show only paid employees
    } elseif ($filter == 'unpaid') {
        $sql = "SELECT e.* FROM employees e
                LEFT JOIN salarypayments sp ON e.id = sp.employee_id
                WHERE sp.employee_id IS NULL OR sp.payment_status != 'Paid'
                GROUP BY e.id"; // Show only unpaid employees
    } else {
        $sql = "SELECT e.* FROM employees e"; // Show all employees
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["position"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["salary"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td class='action-buttons'>";
            echo "<form method='GET' action='pay_salary.php'>"; // Use GET to pass employee_id
            echo "<input type='hidden' name='employee_id' value='" . htmlspecialchars($row["id"]) . "'>";
            echo "<button type='submit'>Pay</button>"; // Pay button redirects to pay_salary.php with the employee ID
            echo "</form>";
            echo "<form method='POST' action='delete_employee.php'>";  //delete
            echo "<input type='hidden' name='employee_id' value='" . $row["id"] . "'>";
            echo "<button type='submit' onclick=\"return confirm('Are you sure you want to delete this employee?');\">Delete</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8' style='text-align: center;'>No employees found</td></tr>";
        echo "<script>alert('No employees found');</script>";
    }
    ?>
</table>

<br><br><br>
<footer>
    <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
</footer>
</body>
</html>
