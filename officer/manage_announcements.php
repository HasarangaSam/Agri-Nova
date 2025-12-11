<?php
session_start();

// -------------------------------------------------------------
//     CHECK LOGIN & ROLE
// -------------------------------------------------------------
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    header("Location: ../login.php");
    exit;
}

$officer_id = $_SESSION['user_id'];  // logged officer ID
$activePage = "announcements";

$alertMessage = "";
$alertType = "success";

if (isset($_GET['added'])) {
    $alertMessage = "Announcement added successfully!";
} elseif (isset($_GET['updated'])) {
    $alertMessage = "Announcement updated successfully!";
} elseif (isset($_GET['deleted'])) {
    $alertMessage = "Announcement deleted successfully!";
} elseif (isset($_GET['error'])) {
    $alertMessage = "Something went wrong!";
    $alertType = "danger";
}

include "sidebar_officer.php";
require "../includes/db.php";

// -------------------------------------------------------------
//     FETCH OFFICER DISTRICT NAME
// -------------------------------------------------------------
// Correct query: join officers → districts to get district_name
$stmt = $conn->prepare("
    SELECT d.district_name
    FROM officers o
    LEFT JOIN districts d ON o.district_id = d.district_id
    WHERE o.officer_id = ?
");
$stmt->bind_param("i", $officer_id);
$stmt->execute();
$stmt->bind_result($officer_district); // holds district name
$stmt->fetch();
$stmt->close();

// -------------------------------------------------------------
//     FETCH ANNOUNCEMENTS FOR THIS OFFICER
// -------------------------------------------------------------
$stmt = $conn->prepare("
    SELECT * 
    FROM district_announcements
    WHERE officer_id = ?
    ORDER BY announcement_id DESC
");
$stmt->bind_param("i", $officer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Announcements - Agri Officer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin-left: 250px; background: #f4f8ff; }

        .page-header-box {
            border-left: 6px solid #1e40af;
            background: #e0e7ff;
            padding: 12px 18px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .page-header-box h2 { 
            margin: 0; 
            font-size: 1.6rem; 
            color: #1e40af; 
            font-weight: 700; 
        }

        thead.table-primary {
            background: #dbeafe !important;
            color: #1e3a8a !important;
        }

        .badge-new {
            background-color: #28a745;
            color: #fff;
            font-size: 0.7rem;
            padding: 3px 6px;
            border-radius: 5px;
            margin-left: 5px;
        }
    </style>
</head>
<body>

<div class="container py-4">

    <!-- Alerts -->
    <?php if ($alertMessage): ?>
        <div class="alert alert-<?= $alertType ?>"><?= $alertMessage ?></div>
    <?php endif; ?>

    <div class="page-header-box">
        <h2>Manage Announcements</h2>
    </div>

    <!-- Add Announcement -->
    <div class="d-flex justify-content-end mb-3">
        <a href="add_announcement.php" class="btn btn-primary">+ Add Announcement</a>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>District</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Created</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result->num_rows === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted">No announcements found.</td></tr>

                    <?php else: 
                        while ($row = $result->fetch_assoc()): 
                            // Check if the announcement is new (<7 days)
                            $created_at = strtotime($row['created_at']);
                            $isNew = (time() - $created_at) <= (7 * 24 * 60 * 60);
                        ?>
                            <tr>
                                <td><?= $row['announcement_id'] ?></td>
                                <td><?= htmlspecialchars($officer_district) ?></td>
                                <td>
                                    <?= htmlspecialchars($row['title']) ?>
                                    <?php if ($isNew): ?>
                                        <span class="badge-new">New</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars(substr($row['message'], 0, 60)) ?>...</td>
                                <td><?= $row['created_at'] ?></td>

                                <td>
                                    <a href="edit_announcement.php?id=<?= $row['announcement_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_announcement.php?id=<?= $row['announcement_id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Delete this announcement?');">
                                       Delete
                                    </a>
                                </td>
                            </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
