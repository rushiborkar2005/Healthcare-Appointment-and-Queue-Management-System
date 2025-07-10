<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['patient_id'])) {
        die("Error: patient_id not found in session.");
    }

    $patient_id = $_SESSION['patient_id'];

    // Collect form data
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $mobile_number = $_POST['mobile_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Initialize image data
    $imageData = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
        } else {
            die("Uploaded file is not a valid image.");
        }
    }

    // Use INSERT ON DUPLICATE KEY UPDATE to avoid duplicate errors
    $query = "INSERT INTO patients_profile 
        (patient_id, name, gender, dob, mobile_number, email, address, image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            name = VALUES(name),
            gender = VALUES(gender),
            dob = VALUES(dob),
            mobile_number = VALUES(mobile_number),
            email = VALUES(email),
            address = VALUES(address),
            image = VALUES(image)";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "isssssss",
        $patient_id,
        $name,
        $gender,
        $dob,
        $mobile_number,
        $email,
        $address,
        $imageData
    );

    if ($stmt->execute()) {
        header("Location: ../Frontend/Patient/Profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
