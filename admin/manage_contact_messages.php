<?php
// ---------------------------------------------------------
// Manage Contact Messages - Admin Panel
// ---------------------------------------------------------

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$activePage = "contacts";  // highlight sidebar item

include "sidebar.php";
require "../includes/db.php";

$alertMessage = "";
$alertType = "success";

// Alerts
if (isset($_GET['responded'])) {
    $alertMessage = "Response email sent successfully!";
    $alertType = "success";
} elseif (isset($_GET['deleted'])) {
    $alertMessage = "Message deleted successfully!";
    $alertType = "success";
} elseif (isset($_GET['error'])) {
    $alertMessage = "Something went wrong!";
    $alertType = "danger";
}

// Fetch all contact messages
// Filter
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

if ($statusFilter === 'pending') {
    $query = "SELECT * FROM contact_messages WHERE status='pending' ORDER BY message_id DESC";
} elseif ($statusFilter === 'responded') {
    $query = "SELECT * FROM contact_messages WHERE status='responded' ORDER BY message_id DESC";
} else {
    $query = "SELECT * FROM contact_messages ORDER BY message_id DESC";
}

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Contact Messages - AgriNova Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { 
            margin-left: 250px; 
            background: #f5f6fa; 
        }

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

        .table thead {
            background: #d7f2d7;
            color: #2e7d32;
        }

        .badge-status {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-pending { background:#fff3cd; color:#a67c00; }
        .status-responded { background:#d4edda; color:#1b5e20; }

        .action-btn {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        @media(max-width:768px){
            body { margin-left:0; }
        }
    </style>
</head>

<body>

<div class="container mt-4">

    <!-- ALERT -->
    <?php if ($alertMessage): ?>
        <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
            <?= $alertMessage ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- PAGE HEADER -->
    <div class="page-header-box">
        <h2>Manage Contact Messages</h2>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm">
        <div class="card-body">

        <!-- FILTER DROPDOWN -->
<form method="GET" class="mb-3">
    <div class="row g-2">

        <div class="col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= ($statusFilter=='all')?'selected':'' ?>>All</option>
                <option value="pending" <?= ($statusFilter=='pending')?'selected':'' ?>>Pending</option>
                <option value="responded" <?= ($statusFilter=='responded')?'selected':'' ?>>Responded</option>
            </select>
        </div>

    </div>
</form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Received On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                    $count = 1;
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $count++; ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>

                            <td style="max-width: 230px;">
                                <div class="text-truncate" style="max-width: 220px;">
                                    <?= htmlspecialchars($row['message']) ?>
                                </div>
                            </td>

                            <td>
                                <?php if ($row['status'] === 'pending'): ?>
                                    <span class="badge-status status-pending">Pending</span>
                                <?php else: ?>
                                    <span class="badge-status status-responded">Responded</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $row['created_at'] ?></td>

                            <td>
                                <!-- View -->
                                <a href="view_contact_message.php?id=<?= $row['message_id'] ?>" 
                                   class="btn btn-primary btn-sm action-btn">
                                   View
                                </a>

                                <!-- Respond (only if pending) -->
                                <?php if ($row['status'] === 'pending'): ?>
                                <a href="respond_contact_message.php?id=<?= $row['message_id'] ?>" 
                                   class="btn btn-success btn-sm action-btn">
                                   Respond
                                </a>
                                <?php endif; ?>

                                <!-- Delete -->
                                <a href="delete_contact_message.php?id=<?= $row['message_id'] ?>" 
                                   onclick="return confirm('Are you sure you want to delete this message?');"
                                   class="btn btn-danger btn-sm action-btn">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
