<?php
// --------------------------------------------------------------
// AGRINOVA - SIGNUP PAGE
// Public page - no session check needed.
// Farmers create an account here.
// --------------------------------------------------------------

$activePage = "signup";
include "includes/navbar.php";
require "includes/db.php"; // DB needed to fetch districts

// Fetch all districts from the database
$districtQuery = $conn->query("SELECT district_id, district_name FROM districts ORDER BY district_name ASC");
$districts = [];
if ($districtQuery->num_rows > 0) {
    while ($row = $districtQuery->fetch_assoc()) {
        $districts[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - AgriNova</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- BOOTSTRAP CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f3fff5;
        }
        .signup-card {
            max-width: 600px;
            margin: auto;
            margin-top: 40px;
            margin-bottom: 40px;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
            max-height: 85vh;
            overflow-y: auto;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="signup-card">

        <h3 class="fw-bold text-success text-center mb-3">Create Your Farmer Account</h3>
        <p class="text-center text-muted mb-4">
            Join AgriNova and get access to smart farming tools.
        </p>

        <!-- SIGNUP FORM -->
        <form action="actions/signup_action.php" method="POST">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Birthdate</label>
                    <input type="date" name="birthdate" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- DISTRICT DROPDOWN -->
                <div class="col-md-12">
                    <label class="form-label">District *</label>
                    <select name="district_id" class="form-select" required>
                        <option value="">Select District</option>
                        <?php
                            foreach ($districts as $d) {
                                echo "<option value='{$d['district_id']}'>" . htmlspecialchars($d['district_name']) . "</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="2" class="form-control"></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" minlength="8" required placeholder="At least 8 characters">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password" class="form-control" minlength="8" required placeholder="Re-type password">
                </div>

                <div class="col-12">
                    <button class="btn btn-success w-100 mt-2">Create Account</button>
                </div>
            </div>

        </form>

        <p class="text-center mt-3">
            Already have an account? 
            <a href="login.php" class="text-success fw-bold">Login</a>
        </p>

    </div>
</div>

<?php include "includes/footer.php"; ?>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
