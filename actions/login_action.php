<?php
// --------------------------------------------------------------
// AGRINOVA - LOGIN ACTION
// Handles login for farmers, admins, and officers
// --------------------------------------------------------------

session_start();
require "../includes/db.php";   // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password.'); window.location='login.php';</script>";
        exit;
    }

    $userFound = false;
    $redirectUrl = "../index.php"; // default for farmers

    // ----------------------------
    // 1. Check in farmers table
    // ----------------------------
    $stmt = $conn->prepare("SELECT farmer_id AS id, password, role FROM farmers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'] ?? 'farmer';
            $userFound = true;
            $redirectUrl = "../index.php";
        }
    }

    // ----------------------------
    // 2. Check in admins table
    // ----------------------------
    if (!$userFound) {
        $stmt = $conn->prepare("SELECT admin_id AS id, password, role FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'] ?? 'admin';
                $userFound = true;
                $redirectUrl = "../admin/dashboard.php";
            }
        }
    }

    // ----------------------------
    // 3. Check in officers table
    // ----------------------------
    if (!$userFound) {
        $stmt = $conn->prepare("SELECT officer_id AS id, password, role FROM officers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'] ?? 'officer';
                $userFound = true;
                $redirectUrl = "../officer/dashboard.php";
            }
        }
    }

    if ($userFound) {
        header("Location: $redirectUrl");
        exit;
    } else {
        echo "<script>alert('Invalid email or password.'); window.location='login.php';</script>";
        exit;
    }
}
?>
