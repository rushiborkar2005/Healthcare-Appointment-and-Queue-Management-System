<?php
session_start();
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // First, check in user_patients table
    $stmt = $conn->prepare("SELECT patient_id, password FROM user_patients WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($patient_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['patient_id'] = $patient_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'patient';
            header("Location: ../Frontend/Patient/PatientHomePage.html");
            exit();
        } else {
            echo "Incorrect password.";
            exit();
        }
    }
    $stmt->close();

    // If not found in user_patients, check in user_doctors
    $stmt = $conn->prepare("SELECT doctor_id, password FROM user_doctor WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($doctor_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['doctor_id'] = $doctor_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'doctor';
            header("Location: ../Frontend/Doctor/DoctorHomePage.html");
            exit();
        } else {
            echo "Incorrect password.";
            exit();
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}
$conn->close();
?>
