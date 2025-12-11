<?php 
// --------------------------------------------------------------
// AGRINOVA - ABOUT PAGE
// General public page, no session check needed.
// --------------------------------------------------------------
session_start();
// Store user info if logged in
$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$activePage = "about";   // highlight navbar tab

// Include Navbar
include "includes/navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - AgriNova</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BOOTSTRAP CDN -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
        rel="stylesheet">

    <style>
        /* Fixed navbar */
        nav.navbar { position: fixed; top: 0; width: 100%; z-index: 1030; }
        .about-hero {
            background: linear-gradient(rgba(0, 80, 0, 0.6), rgba(0, 80, 0, 0.6)),
                        url('assets/about_bg.jpg') center/cover no-repeat;
            padding: 120px 0;
            color: #fff;
            text-align: center;
        }
        .about-card {
            border-radius: 12px;
            background: #f2fff5;
            padding: 30px;
        }
        .mission-box {
            border-left: 5px solid #198754;
            padding-left: 15px;
        }
    </style>
</head>

<body>

<!-- HERO SECTION -->
<section class="about-hero">
    <div class="container">
        <h1 class="fw-bold display-5">About AgriNova</h1>
        <p class="lead">Empowering the future of agriculture with intelligent solutions.</p>
    </div>
</section>

<!-- ABOUT CONTENT -->
<div class="container my-5">

    <!-- INTRO -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="about-card shadow-sm">
                <h3 class="fw-bold text-success mb-3">Who We Are</h3>
                <p>
                    AgriNova is a modern digital agriculture platform built to support Sri Lankan farmers 
                    with technology-driven solutions. We combine 
                    <strong>AI-powered disease prediction</strong>, a 
                    <strong>knowledge hub</strong>, weather insights, and 
                    <strong>direct engagement with agriculture officers</strong> to help farmers make 
                    informed decisions and increase productivity.
                </p>
                <p>
                    The platform is designed to be simple, accessible, and beneficial for both experienced 
                    and new farmers—bringing the future of smart farming to your fingertips.
                </p>
            </div>
        </div>
    </div>

    <!-- MISSION & VISION -->
    <div class="row g-4 mb-5">
        
        <div class="col-md-6">
            <div class="p-4 border rounded shadow-sm bg-white mission-box">
                <h4 class="fw-bold text-success">Our Mission</h4>
                <p>
                    To empower farmers with intelligent tools, real-time insights, and agricultural 
                    knowledge that enhances productivity and sustainability.
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-4 border rounded shadow-sm bg-white mission-box">
                <h4 class="fw-bold text-success">Our Vision</h4>
                <p>
                    To become Sri Lanka’s most trusted digital agriculture companion—supporting 
                    communities, improving yields, and embracing innovation.
                </p>
            </div>
        </div>

    </div>

    <!-- KEY FEATURES -->
    <h3 class="fw-bold text-success text-center mb-4">What AgriNova Offers</h3>

    <div class="row g-4 mb-5">

        <div class="col-md-4">
            <div class="p-3 shadow-sm bg-white text-center rounded">
                <h5 class="fw-bold text-success">AI Disease Detection</h5>
                <p class="small">Instant leaf disease prediction using our CNN model.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 shadow-sm bg-white text-center rounded">
                <h5 class="fw-bold text-success">Knowledge Hub</h5>
                <p class="small">Blogs, crop guides, remedies, and farming techniques.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 shadow-sm bg-white text-center rounded">
                <h5 class="fw-bold text-success">Agri Officer Portal</h5>
                <p class="small">Field visit requests, Q&A, announcements and guidance.</p>
            </div>
        </div>

    </div>

</div>

<!-- FOOTER -->
<?php include "includes/footer.php"; ?>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
