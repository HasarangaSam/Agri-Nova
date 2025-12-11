<?php
session_start();
require "../includes/db.php";  

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: contact.php");
    exit;
}

// Sanitize inputs
$name    = trim($_POST['name']);
$email   = trim($_POST['email']);
$message = trim($_POST['message']);

// Basic validation
$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email is required.";
}
if (empty($message)) {
    $errors[] = "Message cannot be empty.";
}

// If validation errors exist → redirect back
if (!empty($errors)) {
    $_SESSION['contact_error'] = implode("<br>", $errors);
    header("Location: ../contact.php");
    exit;
}

// Insert into database
$stmt = $conn->prepare("
    INSERT INTO contact_messages (name, email, message) 
    VALUES (?, ?, ?)
");

$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    $_SESSION['contact_success'] = "Thank you! Your message has been sent successfully.";
} else {
    $_SESSION['contact_error'] = "Something went wrong. Please try again.";
}

$stmt->close();
$conn->close();

// Redirect back to contact page
header("Location: ../contact.php");
exit;
?>
