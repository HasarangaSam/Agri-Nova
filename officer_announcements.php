<?php
// ---------------------------------------------------------
// FARMER PORTAL - VIEW DISTRICT ANNOUNCEMENTS
// ---------------------------------------------------------

session_start();

// Ensure farmer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'farmer') {
    header("Location: login.php");
    exit;
}

require "includes/db.php";
include "includes/navbar.php";

// Get logged-in farmer info
$farmer_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, last_name, district_id FROM farmers WHERE farmer_id = ?");
$stmt->bind_param("i", $farmer_id);
$stmt->execute();
$farmerResult = $stmt->get_result();
$farmer = $farmerResult->fetch_assoc();
$farmerDistrictId = $farmer['district_id'];
$stmt->close();

// Fetch announcements for farmer's district
$stmt = $conn->prepare("
    SELECT da.announcement_id, da.title, da.message, da.created_at,
           o.first_name AS officer_first, o.last_name AS officer_last,
           d.district_name
    FROM district_announcements da
    JOIN officers o ON da.officer_id = o.officer_id
    JOIN districts d ON o.district_id = d.district_id
    WHERE o.district_id = ?
    ORDER BY da.created_at DESC
");
$stmt->bind_param("i", $farmerDistrictId);
$stmt->execute();
$announcementsResult = $stmt->get_result();
$announcements = [];
while ($row = $announcementsResult->fetch_assoc()) {
    $announcements[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>District Announcements - AgriNova</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f7fdf8; }
        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e9f7ee;
            padding: 20px 25px;
            border-radius: 8px;
            margin: 30px auto 20px;
            max-width: 900px;
            text-align: center;
        }
        .announcement-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e0e0e0;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.12);
        }
        .announcement-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .announcement-meta {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 12px;
        }
        .badge-new {
            background-color: #d32f2f;
            color: #fff;
            font-size: 0.7rem;
            margin-left: 8px;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="page-header-box">
        <h2 class="mb-2">District Announcements</h2>
        <p class="text-muted mb-0">Showing the latest updates from your district’s Agri Officers.</p>
    </div>

    <!-- Announcements -->
    <div class="row justify-content-center">
        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $ann): ?>
                <?php
                    $createdDate = new DateTime($ann['created_at']);
                    $today = new DateTime();
                    $interval = $today->diff($createdDate)->days;
                    $isNew = ($interval <= 7);
                ?>
                <div class="col-lg-6 col-md-8 col-sm-12">
                    <div class="announcement-card">
                        <div class="announcement-title">
                            <?= htmlspecialchars($ann['title']) ?>
                            <?php if ($isNew): ?>
                                <span class="badge badge-new">New</span>
                            <?php endif; ?>
                        </div>
                        <div class="announcement-meta">
                            By Officer: <?= htmlspecialchars($ann['officer_first'] . " " . $ann['officer_last']) ?> |
                            District: <?= htmlspecialchars($ann['district_name']) ?> |
                            <?= date('M d, Y', strtotime($ann['created_at'])) ?>
                        </div>
                        <div class="announcement-message">
                            <?= nl2br(htmlspecialchars($ann['message'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted mt-4">
                No announcements found for your district.
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
