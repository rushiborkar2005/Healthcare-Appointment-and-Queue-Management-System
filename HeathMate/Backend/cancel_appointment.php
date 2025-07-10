<?php
// Include DB connection
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token_number = $_POST['token_number'] ?? '';

    if (empty($token_number)) {
        echo "Token number is required.";
        exit;
    }

    // Prepare and execute delete query
    $sql = "DELETE FROM appointments WHERE token_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token_number);

    if ($stmt->execute()) {
        // Redirect back or show success message
        header("Location: ../Frontend/Patient/MyAppointments.php?msg=updated");
        exit;
    } else {
        echo "Failed to cancel appointment. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
?>
