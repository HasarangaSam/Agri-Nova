<?php
session_start();

// ------- Admin Access Check ---------
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_crop_info.php");
    exit;
}

$crop_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT c.*, a.first_name AS author_name 
                        FROM crop_info c
                        LEFT JOIN admins a ON c.author_id = a.admin_id
                        WHERE crop_id = ?");
$stmt->bind_param("i", $crop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: manage_crop_info.php");
    exit;
}

$crop = $result->fetch_assoc();

$activePage = "crop_info";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Crop - <?= htmlspecialchars($crop['crop_name']) ?> | AgriNova Admin</title>
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

    .crop-img {
        width: 100%;
        max-width: 450px;
        border-radius: 10px;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
    }

    .info-box {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
    }

    .info-title {
        color: #2e7d32;
        font-weight: 600;
        margin-bottom: 5px;
    }
</style>
</head>

<body>

<div class="container mt-4">

    <!-- Header -->
    <div class="page-header-box">
        <h2>View Crop Information</h2>
    </div>

    <div class="row">

        <!-- Image Column -->
        <div class="col-md-4 text-center">
            <?php if (!empty($crop['image'])): ?>
                <img src="../uploads/images/crops/<?= htmlspecialchars($crop['image']) ?>" 
                     class="crop-img" alt="Crop Image">
            <?php else: ?>
                <p class="text-muted">No Image Available</p>
            <?php endif; ?>
        </div>

        <!-- Details Column -->
        <div class="col-md-8">
            <div class="info-box">

                <h4 class="mb-3" style="color:#2e7d32; font-weight:700;">
                    <?= htmlspecialchars($crop['crop_name']) ?>
                </h4>

                <p><strong class="info-title">Description:</strong><br>
                    <?= nl2br(htmlspecialchars($crop['description'])) ?>
                </p>

                <hr>

                <p><strong class="info-title">Soil Type:</strong> <?= htmlspecialchars($crop['soil_type']) ?></p>
                <p><strong class="info-title">Soil pH:</strong> <?= htmlspecialchars($crop['soil_ph']) ?></p>
                <p><strong class="info-title">Optimal Rainfall:</strong> <?= htmlspecialchars($crop['optimal_rainfall']) ?></p>
                <p><strong class="info-title">Optimal Temperature:</strong> <?= htmlspecialchars($crop['optimal_temperature']) ?></p>
                <p><strong class="info-title">Water Requirements:</strong> <?= htmlspecialchars($crop['water_requirements']) ?></p>
                <p><strong class="info-title">Days to Maturity:</strong> <?= htmlspecialchars($crop['days_to_maturity']) ?></p>
                <p><strong class="info-title">Spacing:</strong> <?= htmlspecialchars($crop['spacing']) ?></p>

                <hr>

                <?php if (!empty($crop['diseases'])): ?>
                <p><strong class="info-title">Diseases:</strong><br><?= nl2br(htmlspecialchars($crop['diseases'])) ?></p>
                <?php endif; ?>

                <?php if (!empty($crop['pest_management'])): ?>
                <p><strong class="info-title">Pest Management:</strong><br><?= nl2br(htmlspecialchars($crop['pest_management'])) ?></p>
                <?php endif; ?>

                <hr>

                <p><strong class="info-title">Created On:</strong> 
                    <?= date("Y-m-d H:i A", strtotime($crop['created_at'])) ?>
                </p>

                <p><strong class="info-title">Author:</strong> 
                    <?= htmlspecialchars($crop['author_name'] ?? "Unknown") ?>
                </p>

            </div>
        </div>
    </div>

</div>

</body>
</html>
