<?php
// ---------------------------------------------------------
// Agri Officer Sidebar
// ---------------------------------------------------------

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if officer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit;
}

// Active page highlighting
$activePage = $activePage ?? '';
?>
<style>
    /* Sidebar styling */
    .sidebar {
        width: 250px;
        height: 100vh;
        background: #e8f0ff; /* Light Blue */
        position: fixed;
        top: 0;
        left: 0;
        border-right: 1px solid #b8cffb;
        overflow-y: auto;
        transition: all 0.3s ease;
    }

    /* Profile section */
    .profile-wrapper {
        text-align: center;
        padding: 20px 10px 5px;
    }

    .profile-wrapper img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #8ab4f8;
        background: white;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
    }

    .sidebar-header {
        padding: 0.5rem 1rem 1rem;
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a4fb3;
        text-align: center;
        background: #d7e3ff;
        border-bottom: 1px solid #b8cffb;
    }

    .sidebar .nav-link {
        display: block;
        padding: 12px 16px;
        margin: 4px 10px;
        border-radius: 8px;
        font-weight: 500;
        color: #1a2e66;
        background: #ffffff;
        transition: all 0.2s ease;
        box-shadow: 0px 1px 3px rgba(0,0,0,0.08);
    }

    .sidebar .nav-link:hover {
        background: #cfe0ff;
        color: #08368b;
        transform: translateX(3px);
    }

    .sidebar .nav-link.active {
        background: #4285f4;
        color: white !important;
        font-weight: bold;
        box-shadow: 0px 2px 5px rgba(0,0,0,0.15);
    }

    .section-title {
        text-transform: uppercase;
        font-size: 0.75rem;
        margin-top: 20px;
        margin-left: 15px;
        margin-bottom: 5px;
        color: #1a4fb3;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .sidebar { width: 200px; }
        body { margin-left: 200px !important; }
    }

    @media (max-width: 576px) {
        .sidebar {
            position: relative;
            width: 100%;
            height: auto;
        }
        body { margin-left: 0 !important; }
    }
</style>

<div class="sidebar">

    <!-- Profile Picture -->
    <div class="profile-wrapper">
        <img src="../assets/images/admin.png" alt="Officer Profile">
    </div>

    <div class="sidebar-header">
        Agri Officer
    </div>

    <ul class="nav flex-column mt-2">

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= ($activePage==='dashboard') ? 'active' : '' ?>">Dashboard</a>
        </li>

        <!-- Farmers -->
        <li><a href="view_farmers.php" class="nav-link <?= ($activePage==='farmers') ? 'active' : '' ?>">View Farmers</a></li>

        <!-- Predictions -->
        <li><a href="view_predictions.php" class="nav-link <?= ($activePage==='predictions') ? 'active' : '' ?>">View Predictions</a></li>

        <!-- Announcements -->
        <li><a href="manage_announcements.php" class="nav-link <?= ($activePage==='announcements') ? 'active' : '' ?>">Manage Announcements</a></li>

        <!-- Field Visits -->
        <li><a href="manage_field_visits.php" class="nav-link <?= ($activePage==='field_visits') ? 'active' : '' ?>">Manage Field Visits</a></li>

        <!-- Questions -->
        <li><a href="manage_questions.php" class="nav-link <?= ($activePage==='questions') ? 'active' : '' ?>">Manage Questions</a></li>

        <!-- Logout -->
        <li class="mt-3">
            <a href="../logout.php" class="nav-link text-danger" style="background:#ffebee;">Logout</a>
        </li>

    </ul>
</div>
