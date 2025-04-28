<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SalarySync - Choose Login</title>
    <link rel="stylesheet" href="login-style.css"> <!-- Include your styles here -->
    <link rel="shortcut icon" href="shrt.png">
    
</head>
<body>

<div class="container">
<div class="login-form">
    <h2>Welcome to SalarySync</h2>
</div>
    <div class="login-options">
        <!-- Admin Login -->
        <form action="admin_login.php" method="GET">
            <button type="submit">Login as Admin</button>
        </form>

        <!-- Employee Login -->
        <form action="emp_login.php" method="GET">
            <button type="submit">Login as Employee</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 SalarySync: Employee Salary Management. All rights reserved.</p>
</footer>

</body>
</html>
