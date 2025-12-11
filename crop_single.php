<?php
// ---------------------------------------------------------
// SINGLE CROP VIEW PAGE
// Shows:
// - Crop Name (topic)
// - Description + image on right side
// - Key Info table
// - Accordion for Diseases & Pest Management
// ---------------------------------------------------------

session_start();

$activePage = "knowledge";
$activeDropdown = "crop_info";

include "includes/navbar.php";
require "includes/db.php";

// ----------------------------------------------
// Get Crop ID
// ----------------------------------------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: crop_info.php");
    exit;
}

$crop_id = (int)$_GET['id'];

// ----------------------------------------------
// Fetch crop info
// ----------------------------------------------
$stmt = $conn->prepare("SELECT * FROM crop_info WHERE crop_id = ?");
$stmt->bind_param("i", $crop_id);
$stmt->execute();
$crop = $stmt->get_result()->fetch_assoc();

if (!$crop) {
    header("Location: crop_info.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($crop['crop_name']) ?> - Crop Info | AgriNova</title>

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

        .description-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .crop-image {
            max-width: 330px;
            border-radius: 10px;
            float: right;
            margin-left: 20px;
            margin-bottom: 15px;
        }

        @media(max-width: 768px){
            .crop-image {
                float: none;
                width: 100%;
                margin: 0 auto 20px;
                display: block;
            }
        }

        .key-info-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-top: 35px;
            color: #2e7d32;
        }

        .key-info-table th {
            width: 35%;
            font-weight: bold;
            background: #e8f5e9;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Page Header -->
    <div class="page-header-box">
        <h2 class="mb-0"><?= htmlspecialchars($crop['crop_name']) ?></h2>
    </div>

    <!-- Description + Image -->
    <div class="description-box shadow-sm">
    <?php if (!empty($crop['image'])): ?>
        <img src="uploads/images/crops/<?= htmlspecialchars($crop['image']) ?>" 
             class="crop-image" alt="<?= htmlspecialchars($crop['crop_name']) ?>">
    <?php endif; ?>

    <p style="text-align: justify;">
        <?= nl2br(htmlspecialchars($crop['description'])) ?>
    </p>
</div>


    <!-- Key Info -->
    <h3 class="key-info-title">Key Info</h3>

    <div class="table-responsive">
        <table class="table table-bordered key-info-table shadow-sm bg-white">
            <tbody>
                <tr>
                    <th>Soil Type</th>
                    <td><?= htmlspecialchars($crop['soil_type']) ?></td>
                </tr>
                <tr>
                    <th>Soil pH</th>
                    <td><?= htmlspecialchars($crop['soil_ph']) ?></td>
                </tr>
                <tr>
                    <th>Optimal Rainfall</th>
                    <td><?= htmlspecialchars($crop['optimal_rainfall']) ?></td>
                </tr>
                <tr>
                    <th>Optimal Temperature</th>
                    <td><?= htmlspecialchars($crop['optimal_temperature']) ?></td>
                </tr>
                <tr>
                    <th>Water Requirements</th>
                    <td><?= htmlspecialchars($crop['water_requirements']) ?></td>
                </tr>
                <tr>
                    <th>Days to Maturity</th>
                    <td><?= htmlspecialchars($crop['days_to_maturity']) ?></td>
                </tr>
                <tr>
                    <th>Spacing</th>
                    <td><?= htmlspecialchars($crop['spacing']) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Accordion for Diseases & Pest Management -->
    <div class="accordion mt-4 mb-5" id="cropAccordion">

        <!-- Diseases -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#diseases" aria-expanded="true">
                    Diseases
                </button>
            </h2>
            <div id="diseases" class="accordion-collapse collapse show" data-bs-parent="#cropAccordion">
                <div class="accordion-body">
                    <?= nl2br(htmlspecialchars($crop['diseases'])) ?>
                </div>
            </div>
        </div>

        <!-- Pest Management -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#pest" aria-expanded="false">
                    Pest Management
                </button>
            </h2>
            <div id="pest" class="accordion-collapse collapse" data-bs-parent="#cropAccordion">
                <div class="accordion-body">
                    <?= nl2br(htmlspecialchars($crop['pest_management'])) ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
