<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit;
}

$activePage = "dashboard";

include "sidebar_officer.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Officer Dashboard - AgriNova</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin-left: 250px; background: #f0f4ff; }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="page-header-box" style="
        border-left: 6px solid #1a4fb3;
        background: #e0e9ff;
        padding: 12px 18px;
        border-radius: 6px;
    ">
        <h2 style="color:#1a4fb3; font-weight:700;">Agri Officer Dashboard</h2>
    </div>

    <p>Welcome to the Agri Officer Dashboard!</p>

</div>

</body>
</html>
