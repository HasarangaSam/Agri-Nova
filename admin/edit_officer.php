<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "officers"; // highlight sidebar
require "../includes/db.php";

// District list
// Fetch all districts from the database
$districtQuery = $conn->query("SELECT district_id, district_name FROM districts ORDER BY district_name ASC");
$districts = [];
if ($districtQuery->num_rows > 0) {
    while ($row = $districtQuery->fetch_assoc()) {
        $districts[] = $row;
    }
}

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_officers.php?error=invalid_id");
    exit;
}

$officer_id = intval($_GET['id']);

// Fetch officer
$stmt = $conn->prepare("SELECT * FROM officers WHERE officer_id = ?");
$stmt->bind_param("i", $officer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_officers.php?error=not_found");
    exit;
}

$officer = $result->fetch_assoc();
$stmt->close();

$alertMessage = "";
$alertType = "";

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name     = trim($_POST['first_name']);
    $last_name      = trim($_POST['last_name']);
    $email          = trim($_POST['email']);
    $contact_number = trim($_POST['contact_number']);
    $district_id = $_POST['district_id'];
    $password       = trim($_POST['password']);

   
if ($password !== "") {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE officers 
                            SET first_name=?, last_name=?, email=?, contact_number=?, district_id=?, password=? 
                            WHERE officer_id=?");
    $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $contact_number, $district_id, $hashedPassword, $officer_id);
} else {
    $stmt = $conn->prepare("UPDATE officers 
                            SET first_name=?, last_name=?, email=?, contact_number=?, district_id=? 
                            WHERE officer_id=?");
    $stmt->bind_param("ssssii", $first_name, $last_name, $email, $contact_number, $district_id, $officer_id);
}
    if ($stmt->execute()) {
        header("Location: manage_officers.php?updated=1");
        exit;
    } else {
        $alertMessage = "Failed to update officer. Email may already be in use.";
        $alertType = "danger";
    }

    $stmt->close();
}

include "sidebar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Officer - AgriNova Admin</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin-left: 250px; background: #f5f6fa; }

        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f5e5;
            padding: 15px 20px;
            border-radius: 6px;
            margin-top: 25px;
        }

        .page-header-box h3 {
            margin: 0;
            font-weight: 700;
            color: #1b5e20;
        }

        .form-section {
            margin-top: 25px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: #2e7d32;
            border-color: #2e7d32;
        }

        .btn-primary:hover {
            background: #256428;
            border-color: #256428;
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Alert -->
    <?php if ($alertMessage): ?>
        <div class="alert alert-<?= $alertType ?> mt-3"><?= $alertMessage ?></div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header-box">
        <h3>Edit Agri Officer</h3>
    </div>

    <!-- Form -->
    <div class="form-section">
        <form method="POST">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" 
                           value="<?= htmlspecialchars($officer['first_name']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" 
                           value="<?= htmlspecialchars($officer['last_name']) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($officer['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="<?= htmlspecialchars($officer['contact_number']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">District *</label>
                <select name="district_id" class="form-control" required>
                    <?php foreach ($districts as $d): ?>
                        <option value="<?= $d['district_id'] ?>" 
                            <?= ($officer['district_id'] == $d['district_id']) ? 'selected' : '' ?>>
                            <?= $d['district_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="mb-4">
                <label class="form-label">Password (optional)</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </div>

            <button type="submit" class="btn btn-primary px-4">Update Officer</button>
            <a href="manage_officers.php" class="btn btn-secondary">Back</a>

        </form>
    </div>

</div>

</body>
</html>
