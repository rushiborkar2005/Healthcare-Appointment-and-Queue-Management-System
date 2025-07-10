<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['patient_id'])) {
    echo "You must be logged in.";
    exit;
}

$patient_id = intval($_SESSION['patient_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token_number = intval($_POST['token_number'] ?? 0);  // or 'token_number' depending on frontend

    if ($token_number <= 0) {
        echo "Invalid appointment identifier.";
        exit;
    }

    $sql = "SELECT patient_name, patient_age, patient_gender, patient_mobile_number, patient_email, preferred_shift 
            FROM appointments WHERE token_number = ? AND patient_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit;
    }

    $stmt->bind_param('ii', $token_number, $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Appointment not found or access denied.";
        exit;
    }

    $current = $result->fetch_assoc();
    $stmt->close();

    // Use POST data if set and not empty; else keep current values
    $name = trim(string: $_POST['name'] ?? '');
    if ($name === '') $name = $current['patient_name'];

    $age = isset($_POST['age']) ? intval($_POST['age']) : $current['patient_age'];
    if ($age <= 0) $age = $current['patient_age'];

    $gender = trim($_POST['gender'] ?? '');
    if ($gender === '') $gender = $current['patient_gender'];

    $mobile = trim($_POST['mobile'] ?? '');
    if ($mobile === '') $mobile = $current['patient_mobile_number'];

    $email = trim($_POST['email'] ?? '');
    if ($email === '') $email = $current['patient_email'];

    $shift = trim($_POST['preferred_shift'] ?? '');
    if ($shift === '') $shift = $current['preferred_shift'];

    // Validate email if changed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Prepare UPDATE statement
    $sqlUpdate = "UPDATE appointments SET patient_name=?, patient_age=?, patient_gender=?, patient_mobile_number=?, patient_email=?, preferred_shift=? WHERE token_number = ? AND patient_id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    if (!$stmtUpdate) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit;
    }

    $stmtUpdate->bind_param('sissssii', $name, $age, $gender, $mobile, $email, $shift, $token_number, $patient_id);


    if ($stmtUpdate->execute()) {
        header("Location: ../Frontend/Patient/MyAppointments.php?msg=updated");
        exit;
    } else {
        echo "Error updating appointment: " . $stmtUpdate->error;
    }
} else {
    echo "Invalid request method.";
}
?>
