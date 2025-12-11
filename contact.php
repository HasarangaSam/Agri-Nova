<?php
session_start(); 
$user_id = $_SESSION['user_id'] ?? null;
$role = $_SESSION['role'] ?? null;
$activePage = "contact";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AgriNova</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .contact-section {
            padding: 40px 0;
        }
        .map-responsive {
            overflow: hidden;
            padding-bottom: 56.25%;
            position: relative;
            height: 0;
            border-radius: 12px;
        }
        .map-responsive iframe {
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            position: absolute;
            border: 0;
        }
        .contact-title {
            font-weight: 700;
        }
        .contact-description {
            font-size: 1.1rem;
            color: #555;
            max-width: 750px;
            margin: 0 auto 30px auto;
        }
        .shadow-card {
            border-radius: 12px;
        }
        /* Fixed navbar */
    /* nav.navbar { position: fixed; top: 0; width: 100%; z-index: 1030; } */
    </style>
</head>
<body>

<!-- Navbar -->
<?php include 'includes/navbar.php'; ?>

<div class="container contact-section">

    <!-- Page Title -->
    <h2 class="text-center contact-title mb-3">Contact Us</h2>

    <!-- Description -->
    <p class="text-center contact-description">
        Have a question, suggestion, or need support with AgriNova?  
        Send us a message using the form below—our team will get back to you as soon as possible.
    </p>

    <div class="row g-4 mt-5">

    <!-- Success msg -->
    <?php if (isset($_SESSION['contact_success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['contact_success']; ?></div>
    <?php unset($_SESSION['contact_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['contact_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['contact_error']; ?></div>
        <?php unset($_SESSION['contact_error']); ?>
    <?php endif; ?>

        <!-- Contact Form -->
        <div class="col-lg-6">
            <div class="card shadow-sm shadow-card">
                <div class="card-body p-4">

                    <h5 class="fw-semibold mb-3">Send Us a Message</h5>

                    <form action="actions/contact_submit.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Your Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Write your message here..." required></textarea>
                        </div>

                        <button class="btn btn-success w-100">Send Message</button>
                    </form>

                </div>
            </div>
        </div>

        <!-- Google Map -->
        <div class="col-lg-6">
            <h5 class="fw-semibold mb-3">Find Us</h5>

            <div class="map-responsive shadow-sm">

                <!-- Google map -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3977.0774427879636!2d79.97251697404377!3d6.927078593058824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2597b43a0b077%3A0x4c68c44a7d4765a!2sGampaha!5e0!3m2!1sen!2slk!4v1700000000000"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>

            </div>
        </div>

    </div>
</div>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Auto-hide success/error alerts after 5 seconds
    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Add fade-out effect
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";

            // Remove from DOM after fade
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>

</body>
</html>
