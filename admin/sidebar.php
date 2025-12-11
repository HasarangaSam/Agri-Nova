<?php
// ---------------------------------------------------------
// Admin Sidebar
// ---------------------------------------------------------

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
        background: #e9f9e9; /* Light green */
        position: fixed;
        top: 0;
        left: 0;
        border-right: 1px solid #c8e6c9;
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
        border: 3px solid #a5d6a7;
        background: white;
        box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
    }

    .sidebar-header {
        padding: 0.5rem 1rem 1rem;
        font-size: 1.3rem;
        font-weight: 700;
        color: #2e7d32;
        text-align: center;
        background: #d7f2d7;
        border-bottom: 1px solid #c8e6c9;
    }

    .sidebar .nav-link {
        display: block;
        padding: 12px 16px;
        margin: 4px 10px;
        border-radius: 8px;
        font-weight: 500;
        color: #2f4f2f;
        background: #ffffff;
        transition: all 0.2s ease;
        box-shadow: 0px 1px 3px rgba(0,0,0,0.08);
    }

    .sidebar .nav-link:hover {
        background: #c6efc6;
        color: #1b5e20;
        transform: translateX(3px);
    }

    .sidebar .nav-link.active {
        background: #66bb6a;
        color: white !important;
        font-weight: bold;
        box-shadow: 0px 2px 5px rgba(0,0,0,0.15);
    }

    /* Section titles */
    .sidebar .section-title {
        text-transform: uppercase;
        font-size: 0.75rem;
        margin-top: 20px;
        margin-left: 15px;
        margin-bottom: 5px;
        color: #558b2f;
        font-weight: 700;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }
        body {
            margin-left: 200px !important;
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            position: relative;
            width: 100%;
            height: auto;
        }
        body {
            margin-left: 0 !important;
        }
    }
</style>

<div class="sidebar">

    <!-- Profile Picture Added Here -->
    <div class="profile-wrapper">
        <img src="../assets/images/admin.png" alt="Admin Profile">
    </div>

    <div class="sidebar-header">
        AgriNova Admin
    </div>

    <ul class="nav flex-column mt-2">

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?= ($activePage === 'dashboard') ? 'active' : '' ?>">Dashboard</a>
        </li>

        <!-- User Management -->
        <div class="section-title">User Management</div>
        <li><a href="manage_farmers.php" class="nav-link <?= ($activePage==='farmers')?'active':'' ?>">Manage Farmers</a></li>
        <li><a href="manage_officers.php" class="nav-link <?= ($activePage==='officers')?'active':'' ?>">Manage Agri Officers</a></li>
        <li><a href="manage_admins.php" class="nav-link <?= ($activePage==='admins')?'active':'' ?>">Manage Admins</a></li>

        <!-- Content Management -->
        <div class="section-title">Content Management</div>
        <li><a href="manage_blogs.php" class="nav-link <?= ($activePage==='blogs')?'active':'' ?>">Manage Blogs</a></li>
        <li><a href="manage_crop_info.php" class="nav-link <?= ($activePage==='crop_info')?'active':'' ?>">Manage Crop Info</a></li>
        <li><a href="manage_remedies.php" class="nav-link <?= ($activePage==='remedies')?'active':'' ?>">Manage Remedies</a></li>

        <!-- Communication -->
        <div class="section-title">Communication & Support</div>
        <li><a href="manage_announcements.php" class="nav-link <?= ($activePage==='announcements')?'active':'' ?>">Manage Announcements</a></li>
        <li><a href="view_field_visits.php" class="nav-link <?= ($activePage==='field_visits')?'active':'' ?>">Field Visit Requests</a></li>
        <li><a href="asked_questions.php" class="nav-link <?= ($activePage==='asked_questions')?'active':'' ?>">Asked Questions</a></li>
        <li><a href="manage_contact_messages.php" class="nav-link <?= ($activePage==='contacts')?'active':'' ?>">Contact Us</a></li>

        <!-- AI Model Section -->
        <div class="section-title">AI Model</div>
        <li><a href="prediction_history.php" class="nav-link <?= ($activePage==='prediction_history')?'active':'' ?>">Prediction History</a></li>
        <li><a href="feedback_ml.php" class="nav-link <?= ($activePage==='feedback_ml')?'active':'' ?>">AI Model Feedback</a></li>

        <!-- Logout -->
        <li class="mt-3">
            <a href="../logout.php" class="nav-link text-danger" style="background:#ffebee;">Logout</a>
        </li>
    </ul>
</div>
