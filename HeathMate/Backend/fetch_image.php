<?php
require '../../Backend/db_connection.php';

if (!isset($_GET['patient_id'])) {
    http_response_code(400);
    echo "Missing patient ID.";
    exit;
}

$patient_id = intval($_GET['patient_id']);

$query = "SELECT image FROM patients_profile WHERE patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $imageData = $row['image'];

    if ($imageData) {
        header("Content-Type: image/jpeg"); // or image/png, depending on how you stored it
        echo $imageData;
    } else {
        // Show a default placeholder image
        header("Content-Type: image/png");
        readfile("../assets/default_profile.png");
    }
} else {
    http_response_code(404);
    echo "Image not found.";
}

$stmt->close();
$conn->close();
?>
