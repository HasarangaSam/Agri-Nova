<?php
// --------------------------------------------------------------
// AGRINOVA - SIGNUP ACTION
// Handles form submission for normal signup (no email verification).
// --------------------------------------------------------------

require "../includes/db.php";    // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ----------------------------
    // CLEAN INPUTS
    // ----------------------------
    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $birth = $_POST['birthdate'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $district = $_POST['district'] ?? null;
    $address = trim($_POST['address']);
    $phone = trim($_POST['contact_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ----------------------------
    // PASSWORD VALIDATION
    // ----------------------------
    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.'); window.location='signup.php';</script>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.location='signup.php';</script>";
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // ----------------------------
    // CHECK IF EMAIL ALREADY EXISTS
    // ----------------------------
    $check = $conn->prepare("SELECT farmer_id FROM farmers WHERE email = ?");
    if (!$check) {
        die("Prepare failed: " . $conn->error);
    }
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Email already registered! Try logging in.'); window.location='signup.php';</script>";
        exit;
    }

    // ----------------------------
    // INSERT NEW FARMER INTO DB
    // ----------------------------
    $sql = $conn->prepare("
        INSERT INTO farmers (
            first_name, last_name, email, birthdate, gender, district,
            address, contact_number, password
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$sql) {
        die("Prepare failed: " . $conn->error);
    }

    $sql->bind_param(
        "sssssssss",
        $first, $last, $email, $birth, $gender, $district,
        $address, $phone, $password_hash
    );

    if ($sql->execute()) {
        echo "<script>alert('Signup successful! You can now login.'); window.location='../login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error creating account. Try again.'); window.location='signup.php';</script>";
    }
}
?>
