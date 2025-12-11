<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require "../includes/db.php";

if (!isset($_GET['id'])) {
    header("Location: manage_crops.php");
    exit;
}

$crop_id = (int)$_GET['id'];
$message = "";

// Fetch existing crop
$stmt = $conn->prepare("SELECT * FROM crop_info WHERE crop_id = ?");
$stmt->bind_param("i", $crop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_crops.php?notfound=1");
    exit;
}

$crop = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $crop_name          = trim($_POST['crop_name']);
    $description        = trim($_POST['description']);
    $soil_type          = trim($_POST['soil_type']);
    $soil_ph            = trim($_POST['soil_ph']);
    $optimal_rainfall   = trim($_POST['optimal_rainfall']);
    $optimal_temp       = trim($_POST['optimal_temperature']);
    $water_req          = trim($_POST['water_requirements']);
    $maturity           = trim($_POST['days_to_maturity']);
    $spacing            = trim($_POST['spacing']);
    $diseases           = trim($_POST['diseases']);
    $pest_mgmt          = trim($_POST['pest_management']);
    $author_id          = $_SESSION['user_id'];

    $imageName = $crop['image']; // keep existing if no new upload
    $targetDir = "../uploads/images/crops/";

    // ---- Image Upload ----
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $tempName = $_FILES["image"]["tmp_name"];
        $original = basename($_FILES["image"]["name"]);
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($ext, $allowed)) {
            $message = "<div class='alert alert-danger'>Only JPG, JPEG, PNG, WEBP allowed.</div>";
        } elseif (!is_uploaded_file($tempName)) {
            $message = "<div class='alert alert-danger'>Failed to upload image.</div>";
        } else {
            $destination = $targetDir . $original;

            // Delete old image if file name differs
            if (!empty($crop['image']) && $crop['image'] !== $original && file_exists($targetDir . $crop['image'])) {
                unlink($targetDir . $crop['image']);
            }

            if (move_uploaded_file($tempName, $destination)) {
                $imageName = $original;
            } else {
                $message = "<div class='alert alert-danger'>Failed to move uploaded image.</div>";
            }
        }
    }

    // Update DB if no errors
    if ($message === "") {
        $stmt = $conn->prepare("
            UPDATE crop_info 
            SET crop_name=?, description=?, image=?, soil_type=?, soil_ph=?, 
                optimal_rainfall=?, optimal_temperature=?, water_requirements=?, 
                days_to_maturity=?, spacing=?, diseases=?, pest_management=?, author_id=? 
            WHERE crop_id=?
        ");

        $stmt->bind_param(
            "sssssssssssssi",
            $crop_name, $description, $imageName, $soil_type, $soil_ph,
            $optimal_rainfall, $optimal_temp, $water_req, $maturity, $spacing,
            $diseases, $pest_mgmt, $author_id, $crop_id
        );

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Crop updated successfully!</div>";

            // Refresh crop
            $rs = $conn->prepare("SELECT * FROM crop_info WHERE crop_id=?");
            $rs->bind_param("i", $crop_id);
            $rs->execute();
            $crop = $rs->get_result()->fetch_assoc();

        } else {
            $message = "<div class='alert alert-danger'>Error updating crop.</div>";
        }
    }
}

$activePage = "crop_info";
include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Crop - AgriNova Admin</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { margin-left:250px; background:#f8f9fa; }

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
.current-image { max-height:200px; margin-bottom:15px; border-radius:6px; }
</style>
</head>
<body>

<div class="container mt-4">

    <div class="page-header-box">
        <h2>Edit Crop</h2>
    </div>

    <?= $message ?>

    <div class="card shadow-sm p-4">

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label"><strong>Crop Name</strong></label>
                <input type="text" name="crop_name" class="form-control" value="<?= htmlspecialchars($crop['crop_name']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Description</strong></label>
                <textarea name="description" rows="5" class="form-control" required><?= htmlspecialchars($crop['description']); ?></textarea>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Soil Type</strong></label>
                    <input type="text" name="soil_type" class="form-control" value="<?= htmlspecialchars($crop['soil_type']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><strong>Soil pH</strong></label>
                    <input type="text" name="soil_ph" class="form-control" value="<?= htmlspecialchars($crop['soil_ph']); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Optimal Rainfall</strong></label>
                <input type="text" name="optimal_rainfall" class="form-control" value="<?= htmlspecialchars($crop['optimal_rainfall']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Optimal Temperature</strong></label>
                <input type="text" name="optimal_temperature" class="form-control" value="<?= htmlspecialchars($crop['optimal_temperature']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Water Requirements</strong></label>
                <input type="text" name="water_requirements" class="form-control" value="<?= htmlspecialchars($crop['water_requirements']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Days to Maturity</strong></label>
                <input type="text" name="days_to_maturity" class="form-control" value="<?= htmlspecialchars($crop['days_to_maturity']); ?>">
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Spacing</strong></label>
                <input type="text" name="spacing" class="form-control" value="<?= htmlspecialchars($crop['spacing']); ?>">
            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label"><strong>Diseases</strong></label>
                <textarea name="diseases" rows="4" class="form-control"><?= htmlspecialchars($crop['diseases']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Pest Management</strong></label>
                <textarea name="pest_management" rows="4" class="form-control"><?= htmlspecialchars($crop['pest_management']); ?></textarea>
            </div>

            <hr>

            <?php if (!empty($crop['image'])): ?>
                <label class="form-label"><strong>Current Image</strong></label><br>
                <img src="../uploads/images/crops/<?= htmlspecialchars($crop['image']); ?>" class="current-image">
                <br>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label"><strong>Replace Image (optional)</strong></label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="text-end">
                <a href="manage_crop_info.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Update Crop</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
