<?php
include 'db.php';

if (isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // Query to fetch employee name
    $sql = "SELECT name FROM employees WHERE id='$employee_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo htmlspecialchars($row['name']);
    } else {
        echo "Employee not found";
    }
}
?>