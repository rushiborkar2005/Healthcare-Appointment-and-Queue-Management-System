<?php
session_start();
require 'db_connection.php'; // include your connection here

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role']; // 'patient' or 'doctor'

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($role === 'patient') {
        $stmt = $conn->prepare("INSERT INTO user_patients (username, email, password) VALUES (?, ?, ?)");
    } elseif ($role === 'doctor') {
        $stmt = $conn->prepare("INSERT INTO user_doctor (username, email, password) VALUES (?, ?, ?)");
    } else {
        echo "Invalid role selected.";
        exit;
    }

    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful.";
         header("Location: ../Frontend/loginPage.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
