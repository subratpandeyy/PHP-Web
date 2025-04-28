<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "medlink";
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = "";
$appointmentDetails = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_name = $_POST['patient_name'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    $stmt = $conn->prepare("INSERT INTO online_appointments (patient_name, doctor_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("Error preparing the SQL statement: " . $conn->error);
    }
    
    $stmt->bind_param("siss", $patient_name, $doctor_id, $appointment_date, $appointment_time);

    if ($stmt->execute()) {
        $successMessage = "Online appointment booked successfully!";
        
        // Fetch the last inserted appointment
        $appointment_id = $stmt->insert_id; // Get the ID of the last inserted record
        $stmt = $conn->prepare("SELECT * FROM online_appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $appointmentDetails = $result->fetch_assoc();
        }
        $stmt->close();
    } else {
        $successMessage = "Error: " . $stmt->error;
    }
}

// Fetch doctors
$doctors = $conn->query("SELECT id, name FROM doctors");

// Check if the query returned any rows
if (!$doctors || $doctors->num_rows == 0) {
    die("No doctors found in the database.");
}

// Fetch available times for a selected doctor
$available_times = [];
if (isset($_GET['doctor_id'])) {
    $doctor_id = $_GET['doctor_id'];
    $available_times = $conn->query("SELECT time_slot FROM available_times WHERE doctor_id = $doctor_id");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Online Appointment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="login-section">
<div class="login-container">
<h2>Book an Online Appointment with a Doctor</h2>

<form method="post" action="">

    <div class="form-group">
        <label for="doctor_id">Choose a Doctor:</label>
        <select id="doctor_id" name="doctor_id" onchange="updateAvailableTimes()" required>
            <option value="">Select a doctor</option>
            <?php while ($row = $doctors->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= isset($_GET['doctor_id']) && $_GET['doctor_id'] == $row['id'] ? 'selected' : '' ?>><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div><br><br>
    
    <div class="form-group">
        <label for="patient_name">Your Name:</label>
        <input type="text" id="patient_name" name="patient_name" required>
    </div><br><br>

    <div class="form-group">
        <label for="appointment_date">Choose a Date:</label>
        <input type="date" id="appointment_date" name="appointment_date" required>
    </div><br><br>

    <div class="form-group">
        <label for="appointment_time">Choose a Time:</label>
        <select id="appointment_time" name="appointment_time" required>
            <option value="">Select a time slot</option>
            <?php if (!empty($available_times)): ?>
                <?php while ($row = $available_times->fetch_assoc()): ?>
                    <option value="<?= $row['time_slot'] ?>"><?= $row['time_slot'] ?></option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
    </div><br><br>

    <button type="submit" class="btn">Book Appointment</button>
</form>

<?php if ($successMessage): ?>
    <h3><?= $successMessage ?></h3>
    <?php if ($appointmentDetails): ?>
        <h4>Your Appointment Details:</h4>
        <p><strong>Appointment ID:</strong> <?= $appointmentDetails['id'] ?></p>
        <p><strong>Name:</strong> <?= $appointmentDetails['patient_name'] ?></p>
        <p><strong>Doctor ID:</strong> <?= $appointmentDetails['doctor_id'] ?></p>
        <p><strong>Date:</strong> <?= $appointmentDetails['appointment_date'] ?></p>
        <p><strong>Time:</strong> <?= $appointmentDetails['appointment_time'] ?></p>
        <p><strong>Type:</strong> <?= $appointmentDetails['appointment_type'] ?></p>
    <?php endif; ?>
<?php endif; ?>

</div>


</section>

<script>
function updateAvailableTimes() {
    var doctorId = document.getElementById('doctor_id').value;
    if (doctorId) {
        // Redirect to the same page with the selected doctor_id as a query parameter
        window.location.href = "?doctor_id=" + doctorId;
    } else {
        // Clear available times if no doctor is selected
        document.getElementById('appointment_time').innerHTML = '<option value="">Select a time slot</option>';
    }
}
</script>

</body>
</html>
