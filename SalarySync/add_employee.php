<?php
include 'db.php'; // Include the database connection

try {
    // Fetch all employees' details (excluding payment details)
    $sql = "SELECT name, position, salary, address, phone, email FROM employees";
    $result = $conn->query($sql);

    // Check if query execution was successful
    if ($result === false) {
        throw new Exception("Error in Query: " . $conn->error);
    }
} catch (Exception $e) {
    echo '<p>Error: ' . $e->getMessage() . '</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Employee</title>
    <link rel="stylesheet" href="homestyle.css">
    <link rel="shortcut icon" href="shrt.png">
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
            <li><a href="service.php">Services</a></li>
            <li><a href="about.php">About</a></li>
            <!-- <li><a href="contact.php">Contact Us</a></li> -->
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</div>
<br>

<section id="login-section">
    <div class="add-emp">
        <form action="add_employee.php" method="POST">
            <h1>Add Employee</h1>

            <div class="input-group">
                <input type="text" id="name" name="name" required placeholder="Username">
            </div>

            <div class="input-group">
                <input type="text" id="position" name="position" required placeholder="Position">
            </div>

            <div class="input-group">
                <input type="number" id="salary" name="salary" step="0.01" required placeholder="Salary">
            </div>

            <div class="input-group">
                <input type="text" id="address" name="address" required placeholder="Address">
            </div>

            <div class="input-group">
                <input type="text" id="phone" name="phone" required placeholder="Phone">
            </div>

            <div class="input-group">
                <input type="email" id="email" name="email" required placeholder="Email">
            </div>

            <button type="submit">Add Employee</button>
        </form>
    </div>

    <!-- Display employee names, positions, salary, address, phone, and email -->
    <div class="employee-list">
        <h2>Existing Employees</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Salary</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
            </tr>
            <?php
            if (isset($result) && $result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['position']) . "</td>
                            <td>" . htmlspecialchars($row['salary']) . "</td>
                            <td>" . htmlspecialchars($row['address']) . "</td>
                            <td>" . htmlspecialchars($row['phone']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No employees found</td></tr>";
            }
            ?>
        </table>
    </div>
</section>
<br><br><br>
<footer>
    <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
</footer>

</body>
</html>
