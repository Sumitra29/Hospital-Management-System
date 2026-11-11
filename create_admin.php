<?php
require_once "config.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = "admin";
$email = "admin@hospital.com";
$password = "admin@123";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password, role, status)
        VALUES (?, ?, ?, 'admin', 'active')";

$stmt = $connection->prepare($sql);
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$connection->close();
?>