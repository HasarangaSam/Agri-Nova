<?php
session_start();

// ------- Admin Access Check ---------
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require "../includes/db.php";

$message = "";

// =========================
//   Handle Form Submission
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $crop_name         = trim($_POST['crop_name']);
    $description       = trim($_POST['description']);
    $soil_type         = trim($_POST['soil_type']);
    $soil_ph           = trim($_POST['soil_ph']);
    $optimal_rainfall  = trim($_POST['optimal_rainfall']);
    $optimal_temp      = trim($_POST['optimal_temperature']);
    $water_req         = trim($_POST['water_requirements']);
    $days_to_maturity  = trim($_POST['days_to_maturity']);
    $spacing           = trim($_POST['spacing']);
    $diseases          = trim($_POST['diseases']);
    $pest_management   = trim($_POST['pest_management']);
    $author_id         = $_SESSION['user_id'];

    $imageName = "";

    // -------- Image Upload --------
    if (!empty($_FILES['image']['name'])) {

        $targetDir = "../uploads/images/crops/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $tempName  = $_FILES["image"]["tmp_name"];
        $original  = basename($_FILES["image"]["name"]);
        $ext       = strtolower(pathinfo($original, PATHINFO_EXTENSION));

        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($ext, $allowed)) {
            $message = "<div class='alert alert-danger'>Only JPG, JPEG, PNG, WEBP allowed.</div>";
        } else {
            // Keep original image name
            $imageName = $original;
            move_uploaded_file($tempName, $targetDir . $imageName);
        }
    }

    if ($message === "") {

        $stmt = $conn->prepare("
            INSERT INTO crop_info 
            (crop_name, description, image, soil_type, soil_ph, optimal_rainfall, 
             optimal_temperature, water_requirements, days_to_maturity, spacing, diseases, 
             pest_management, author_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssssssssi",
            $crop_name,
            $description,
            $imageName,
            $soil_type,
            $soil_ph,
            $optimal_rainfall,
            $optimal_temp,
            $water_req,
            $days_to_maturity,
            $spacing,
            $diseases,
            $pest_management,
            $author_id
        );

        if ($stmt->execute()) {
            header("Location: manage_crop_info.php?success=1");
            exit;
        } else {
            $message = "<div class='alert alert-danger'>Error saving crop information.</div>";
        }
    }
}

$activePage = "crop_info";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Crop Information - AgriNova Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body { margin-left: 250px; background: #f8f9fa; }

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

    .card { border-radius:10px; }
</style>
</head>
<body>

<div class="container mt-4">

    <!-- Header -->
    <div class="page-header-box">
        <h2>Add New Crop Information</h2>
    </div>

    <?= $message ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <!-- Crop Name -->
                <div class="mb-3">
                    <label class="form-label">Crop Name</label>
                    <input type="text" name="crop_name" class="form-control" required>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="5" required></textarea>
                </div>

                <!-- Image Upload -->
                <div class="mb-3">
                    <label class="form-label">Crop Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <hr>

                <!-- Soil Type -->
                <div class="mb-3">
                    <label class="form-label">Soil Type</label>
                    <input type="text" name="soil_type" class="form-control">
                </div>

                <!-- Soil pH -->
                <div class="mb-3">
                    <label class="form-label">Soil pH</label>
                    <input type="text" name="soil_ph" class="form-control">
                </div>

                <!-- Optimal Rainfall -->
                <div class="mb-3">
                    <label class="form-label">Optimal Rainfall</label>
                    <input type="text" name="optimal_rainfall" class="form-control">
                </div>

                <!-- Optimal Temperature -->
                <div class="mb-3">
                    <label class="form-label">Optimal Temperature</label>
                    <input type="text" name="optimal_temperature" class="form-control">
                </div>

                <!-- Water Requirements -->
                <div class="mb-3">
                    <label class="form-label">Water Requirements</label>
                    <input type="text" name="water_requirements" class="form-control">
                </div>

                <!-- Days to Maturity -->
                <div class="mb-3">
                    <label class="form-label">Days to Maturity</label>
                    <input type="text" name="days_to_maturity" class="form-control">
                </div>

                <!-- Spacing -->
                <div class="mb-3">
                    <label class="form-label">Spacing</label>
                    <input type="text" name="spacing" class="form-control">
                </div>

                <!-- Diseases -->
                <div class="mb-3">
                    <label class="form-label">Diseases</label>
                    <textarea name="diseases" class="form-control" rows="4"></textarea>
                </div>

                <!-- Pest Management -->
                <div class="mb-3">
                    <label class="form-label">Pest Management</label>
                    <textarea name="pest_management" class="form-control" rows="4"></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Save Crop Info</button>
                    <a href="manage_crop_info.php" class="btn btn-secondary">Cancel</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
