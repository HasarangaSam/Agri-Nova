<?php 
// ---------------------------------------------------------
// NAVBAR COMPONENT FOR AGRINOVA
// Includes: Logo, Home, About, Prediction dropdown,
// Knowledge Hub dropdown, Agri Officer Portal, Contact,
// Login/Signup or Profile icon.
//
// Active tab highlight works using:
//   $activePage      → for top-level menu items
//   $activeDropdown  → for dropdown sub-items
//
// Each page should define:
//   $activePage = "home"; OR "prediction"; OR "blogs"; etc.
//   $activeDropdown = "blogs"; OR "crop_info"; etc.
// ---------------------------------------------------------

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$userLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriNova</title>

    <style>
        /* Highlight for the active menu item */
        .nav-link.active,
        .dropdown-toggle.active {
            font-weight: 600;
            color: #0d6efd !important;
        }
    </style>
</head>

<body>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">

        <!-- Logo + Brand -->
        <a class="navbar-brand fw-bold" href="index.php">
            <img src="assets/images/logo.png" alt="AgriNova" height="40" class="me-2">
            AgriNova
        </a>

        <!-- Hamburger (mobile view) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#agriNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Items -->
        <div class="collapse navbar-collapse" id="agriNav">
            <ul class="navbar-nav ms-auto">

                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link <?= ($activePage === 'home') ? 'active' : '' ?>" href="index.php">
                        Home
                    </a>
                </li>

                <!-- About -->
                <li class="nav-item">
                    <a class="nav-link <?= ($activePage === 'about') ? 'active' : '' ?>" href="about.php">
                        About
                    </a>
                </li>

                <!-- Prediction (Dropdown) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle 
                        <?= ($activePage === 'prediction') ? 'active' : '' ?>" 
                        href="#" role="button" data-bs-toggle="dropdown">
                        Prediction
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item <?= ($activeDropdown === 'plant_disease') ? 'active' : '' ?>"
                               href="plant_disease.php">
                                Plant Diseases Prediction
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?= ($activeDropdown === 'feedback') ? 'active' : '' ?>"
                               href="feedback.php">
                                Feedback
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Knowledge Hub (Dropdown) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle 
                        <?= ($activePage === 'knowledge') ? 'active' : '' ?>" 
                        href="#" role="button" data-bs-toggle="dropdown">
                        Knowledge Hub
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item <?= ($activeDropdown === 'blogs') ? 'active' : '' ?>"
                               href="blogs.php">
                                Blogs
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item <?= ($activeDropdown === 'crop_info') ? 'active' : '' ?>"
                               href="crop_info.php">
                                Crop Info
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Agri Officer Portal -->
                <li class="nav-item">
                    <a class="nav-link <?= ($activePage === 'officer') ? 'active' : '' ?>" href="officer_portal.php">
                        Agri Officer Portal
                    </a>
                </li>

                <!-- Contact -->
                <li class="nav-item">
                    <a class="nav-link <?= ($activePage === 'contact') ? 'active' : '' ?>" href="contact.php">
                        Contact Us
                    </a>
                </li>

                <!-- Login / Signup / Profile -->
                <?php if (!$userLoggedIn) : ?>
                    <!-- Guest (Not Logged In) -->
                    <li class="nav-item">
                        <a class="btn btn-primary ms-3" href="login.php">Login / Signup</a>
                    </li>
                <?php else : ?>
                    <!-- Logged-in User: Show Profile Avatar -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <img src="assets/images/default_avatar.png" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php">Manage Profile</a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

</body>
</html>
