<?php
// ---------------------------------------------------------
// CROP INFO LIST PAGE - PUBLIC VIEW
// Both logged-in farmers and guests can access this page.
// Features:
// - Shows intro paragraph
// - Crop cards (image + name)
// - Click → crop_single.php?id=
// - Responsive grid (3 per row on desktop)
// ---------------------------------------------------------

session_start();

$farmer_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$activePage = "knowledge";
$activeDropdown = "crop_info";

include "includes/navbar.php";
require "includes/db.php";

// Fetch all crops
$query = "SELECT crop_id, crop_name, image FROM crop_info ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crop Information - AgriNova</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f8f9fa; }

        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 12px 18px;
            border-radius: 6px;
            margin: 30px auto 25px;
            max-width: 850px;
            text-align: center;
        }

        .crop-card {
            border-radius: 12px;
            overflow: hidden;
            transition: 0.25s;
            background: #fff;
        }

        .crop-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .crop-img {
            height: 180px;
            width: 100%;
            object-fit: cover;
        }

        .crop-title {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 12px 15px;
            color: #0d0e0eff;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Header -->
    <div class="page-header-box">
        <h2 class="mb-1">Crop Information</h2>
        <p class="text-muted mb-0">Click on each crop card to learn more about its cultivation, requirements, and management.</p>
    </div>

    <!-- Cards Grid -->
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="crop_single.php?id=<?= (int)$row['crop_id'] ?>" class="text-decoration-none">
                    <div class="crop-card shadow-sm">

                        <!-- Image -->
                        <?php if (!empty($row['image'])): ?>
                            <img src="uploads/images/crops/<?= htmlspecialchars($row['image']) ?>" class="crop-img">
                        <?php else: ?>
                            <img src="uploads/images/no_image.jpg" class="crop-img">
                        <?php endif; ?>

                        <!-- Title -->
                        <div class="crop-title">
                            <?= htmlspecialchars($row['crop_name']) ?>
                        </div>

                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>

</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>
