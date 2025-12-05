<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "dashboard";

include "sidebar.php";
require "../includes/db.php";

// Fetch statistics
$totalFarmers = $conn->query("SELECT COUNT(*) as cnt FROM farmers")->fetch_assoc()['cnt'];
$totalOfficers = $conn->query("SELECT COUNT(*) as cnt FROM officers")->fetch_assoc()['cnt'];
$totalBlogs = $conn->query("SELECT COUNT(*) as cnt FROM blogs")->fetch_assoc()['cnt'];
$totalComments = $conn->query("SELECT COUNT(*) as cnt FROM blog_comments")->fetch_assoc()['cnt'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AgriNova</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin-left: 250px;
            background: #f8f9fa;
        }

        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .page-header-box h2 {
            margin: 0;
            font-size: 1.6rem;
            color: #2e7d32;
            font-weight: 700;
        }

        .card {
            margin-bottom: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-4">

    <!-- NEW PAGE HEADER -->
    <div class="page-header-box">
        <h2>Dashboard</h2>
    </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Farmers</h6>
                <h3><?= $totalFarmers ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Officers</h6>
                <h3><?= $totalOfficers ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Blogs</h6>
                <h3><?= $totalBlogs ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h6>Total Comments</h6>
                <h3><?= $totalComments ?></h3>
            </div>
        </div>
    </div>

</div>

</body>
</html>
