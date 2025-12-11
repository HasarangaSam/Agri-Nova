<?php
// ---------------------------------------------------------
// AGRI OFFICER PORTAL - FARMER ACCESS
// District-based actions: announcements, questions, field visits
// ---------------------------------------------------------

session_start();

// Store user info if logged in
$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$activePage = "officer";

include "includes/navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agri Officer Portal - AgriNova</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f7fdf8;
        }

        .portal-header-box {
            border-left: 6px solid #1b5e20;
            background: #e9f7ee;
            padding: 18px 25px;
            border-radius: 8px;
            margin: 35px auto 30px;
            max-width: 900px;
            text-align: center;
        }

        .portal-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 25px;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }

        .portal-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .portal-icon {
            font-size: 45px;
            color: #2e7d32;
            margin-bottom: 18px;
        }

        .portal-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .portal-desc {
            font-size: 0.97rem;
            color: #555;
            line-height: 1.4;
            margin-bottom: 18px;
        }

        .portal-btn {
            background: #2e7d32;
            color: #fff;
            border-radius: 6px;
            padding: 8px 18px;
        }

        .portal-btn:hover {
            background: #1b5e20;
            color: #fff;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Header -->
    <div class="portal-header-box">
        <h2 class="mb-2">Agri Officer Portal</h2>
        <p class="text-muted mb-1">
            Logged-in farmers can view Agri Officer announcements, ask questions, and request field visits from 
            their district-related agriculture officers.
        </p>
    </div>

    <!-- Cards Section -->
    <div class="row justify-content-center">

        <!-- Announcements -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="portal-card shadow-sm text-center">
                <i class="fa-solid fa-bullhorn portal-icon"></i>
                <div class="portal-title">Announcements</div>
                <p class="portal-desc">
                    View the latest agricultural updates shared by your district’s Agri Officer.
                </p>
                <a href="officer_announcements.php" class="btn portal-btn">View</a>
            </div>
        </div>

        <!-- Ask Questions -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="portal-card shadow-sm text-center">
                <i class="fa-solid fa-comments portal-icon"></i>
                <div class="portal-title">Ask Questions</div>
                <p class="portal-desc">
                    Submit your agriculture-related questions directly to your Agri Officer and receive expert guidance.
                </p>
                <a href="ask_question.php" class="btn portal-btn">Ask Now</a>
            </div>
        </div>

        <!-- Field Visit Request -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="portal-card shadow-sm text-center">
                <i class="fa-solid fa-tractor portal-icon"></i>
                <div class="portal-title">Field Visit Request</div>
                <p class="portal-desc">
                    Request an on-site visit from your district Agri Officer for crop issues, land inspection, or expert evaluation.
                </p>
                <a href="request_visit.php" class="btn portal-btn">Request</a>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
