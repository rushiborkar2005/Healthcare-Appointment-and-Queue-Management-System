<?php
session_start();
include 'db_connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Debug session
if (!isset($_SESSION['patient_id'])) {
    die("Session patient_id not set");
}

$patient_id = $_SESSION['patient_id'];
$doctor_id = 1; // TEMP: doctor_id 

// Get POST form data
$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$email = $_POST['email'] ?? '';
$shift = $_POST['shift'] ?? '';
$timestamp = date("Y-m-d H:i:s");

// Debug output
var_dump($patient_id, $doctor_id, $name, $age, $gender, $mobile, $shift);

// Validate
if (empty($name) || empty($age) || empty($gender) || empty($mobile) || empty($shift)) {
    die("All fields are required.");
}

$sql = "INSERT INTO appointments 
(patient_id, patient_name, patient_age, patient_gender, patient_mobile_number, patient_email, preferred_shift, timestamp, doctor_id)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isisssssi", $patient_id, $name, $age, $gender, $mobile, $email, $shift, $timestamp, $doctor_id);

if ($stmt->execute()) {
    echo "Appointment booked successfully!";
    header("Location: ../Frontend/Patient/MyAppointments.php");
    exit(); 
} else {
    echo "Error: " . $stmt->error;  
}

$stmt->close();
$conn->close();

?>
