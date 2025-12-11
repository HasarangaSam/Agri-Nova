<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "officers";  // Highlight sidebar

$alertMessage = "";
$alertType = "success";

if (isset($_GET['success'])) {
    $alertMessage = "Agri Officer added successfully!";
} elseif (isset($_GET['updated'])) {
    $alertMessage = "Agri Officer updated successfully!";
} elseif (isset($_GET['deleted'])) {
    $alertMessage = "Agri Officer deleted successfully!";
} elseif (isset($_GET['error'])) {
    $alertMessage = "Something went wrong!";
    $alertType = "danger";
}

include "sidebar.php";
require "../includes/db.php";

// Fetch all officers
$result = $conn->query("SELECT * FROM officers ORDER BY officer_id");

// Fetch all officers with district names using JOIN
$result = $conn->query("
    SELECT o.*, d.district_name 
    FROM officers o
    LEFT JOIN districts d ON o.district_id = d.district_id
    ORDER BY o.officer_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Agri Officers - AgriNova Admin</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { margin-left: 250px; background: #f5f6fa; }

        .page-header-box {
            border-left: 6px solid #2e7d32;
            background: #e5f4e7;
            padding: 10px 20px;
            margin: 25px 20px;
            border-radius: 8px;
        }

        .action-btn {
            margin-right: 6px;
        }
    </style>
</head>

<body>

<div class="container-fluid">

    <?php if ($alertMessage): ?>
        <div class="alert alert-<?= $alertType ?> mx-3 mt-3"><?= $alertMessage ?></div>
    <?php endif; ?>

    <div class="page-header-box">
        <h3 class="m-0">Manage Agri Officers</h3>
    </div>

    <div class="text-end mb-3 mx-3">
        <a href="add_officer.php" class="btn btn-success">+ Add Agri Officer</a>
    </div>

    <div class="card mx-3 mb-4">
        <div class="card-body">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>District</th>
                        <th>Created At</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $row['officer_id'] ?></td>
                        <td><?= $row['first_name'] . " " . $row['last_name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['contact_number'] ?></td>
                        <td><?= $row['district_name'] ?? 'N/A' ?></td>
                        <td><?= $row['created_at'] ?></td>

                        <td>
                            <a href="edit_officer.php?id=<?= $row['officer_id'] ?>"
                               class="btn btn-sm btn-primary action-btn">Edit</a>

                            <a href="delete_officer.php?id=<?= $row['officer_id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this officer?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No officers found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</body>
</html>
