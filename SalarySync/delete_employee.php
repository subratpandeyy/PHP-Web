<?php
session_start();
include 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];

    // Prepare the SQL statement to delete the employee
    $sql = "DELETE FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        echo "Employee deleted successfully.";
    } else {
        echo "Error deleting employee: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: view_employees.php"); // Redirect back to the employee list after deletion
exit();
?>
