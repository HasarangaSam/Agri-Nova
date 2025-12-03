<?php
// --------------------------------------------------------------
// AGRINOVA - LOGIN PAGE
// Public page - no session check needed
// --------------------------------------------------------------

$activePage = "login";
include "includes/navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - AgriNova</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3fff5;
        }
        .login-card {
            max-width: 400px;
            margin: auto;
            margin-top: 60px;
            margin-bottom: 60px;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<div class="container">
    <div class="login-card">

        <h3 class="fw-bold text-success text-center mb-3">Login</h3>
        <p class="text-center text-muted mb-4">
            Access your AgriNova account
        </p>

        <!-- LOGIN FORM -->
        <form action="actions/login_action.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3 text-end">
                <a href="#" class="text-success">Forgot password?</a>
            </div>

            <div class="d-grid">
                <button class="btn btn-success">Login</button>
            </div>

        </form>

        <p class="text-center mt-3">
            Don't have an account? 
            <a href="signup.php" class="text-success fw-bold">Register</a>
        </p>

    </div>
</div>

<?php include "includes/footer.php"; ?>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
